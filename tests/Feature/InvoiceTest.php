<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'client_name' => 'Jane Client',
            'client_email' => 'jane@example.com',
            'client_company' => 'Acme AG',
            'issue_date' => '2026-09-25',
            'due_date' => '2026-10-25',
            'currency' => 'CHF',
            'status' => 'draft',
            'discount' => '50',
            'tax_rate' => '8.1',
            'send_email' => '1',
            'items' => [
                ['description' => 'Conference planning', 'quantity' => '2', 'unit_price' => '1000'],
                ['description' => 'Delegation support', 'quantity' => '1.5', 'unit_price' => '300'],
                ['description' => '', 'quantity' => '1', 'unit_price' => ''], // blank row is ignored
            ],
        ], $overrides);
    }

    public function test_guests_cannot_access_invoices(): void
    {
        $this->get(route('admin.invoices.index'))->assertRedirect(route('login'));
    }

    public function test_admin_pages_render(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('admin.invoices.index'))->assertOk();
        $this->actingAs($user)->get(route('admin.invoices.create'))->assertOk()->assertSee('Line items');
    }

    public function test_create_invoice_calculates_totals_and_emails_client(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());

        $invoice = Invoice::first();
        $response->assertRedirect(route('admin.invoices.show', $invoice));

        $this->assertSame('INV-2026-0001', $invoice->number);
        $this->assertCount(2, $invoice->items);
        // 2000 + 450 = 2450 ; -50 = 2400 ; VAT 8.1% = 194.40 ; total 2594.40
        $this->assertEquals(2450.00, (float) $invoice->subtotal);
        $this->assertEquals(194.40, (float) $invoice->tax_amount);
        $this->assertEquals(2594.40, (float) $invoice->total);

        $this->assertSame(InvoiceStatus::Sent, $invoice->status);
        $this->assertSame(1, $invoice->sent_count);
        $this->assertNotNull($invoice->sent_at);

        Mail::assertQueued(InvoiceMail::class, fn (InvoiceMail $mail) => $mail->hasTo('jane@example.com') && ! $mail->isUpdate);
    }

    public function test_create_without_email_stays_draft(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload(['send_email' => '0']));

        $invoice = Invoice::first();
        $this->assertSame(InvoiceStatus::Draft, $invoice->status);
        Mail::assertNothingQueued();
    }

    public function test_numbers_increment(): void
    {
        Mail::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());
        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());

        $this->assertSame(['INV-2026-0001', 'INV-2026-0002'], Invoice::orderBy('id')->pluck('number')->all());
    }

    public function test_update_replaces_items_and_emails_updated_invoice(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());
        $invoice = Invoice::first();

        $this->actingAs($user)->put(route('admin.invoices.update', $invoice), $this->payload([
            'discount' => '0',
            'tax_rate' => '0',
            'items' => [['description' => 'Only item', 'quantity' => '1', 'unit_price' => '99.99']],
        ]))->assertRedirect(route('admin.invoices.show', $invoice));

        $invoice->refresh();
        $this->assertCount(1, $invoice->items);
        $this->assertEquals(99.99, (float) $invoice->total);
        $this->assertSame(2, $invoice->sent_count);

        Mail::assertQueued(InvoiceMail::class, fn (InvoiceMail $mail) => $mail->isUpdate);
    }

    public function test_validation_requires_items_and_valid_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload([
            'client_email' => 'not-an-email',
            'items' => [],
        ]))->assertSessionHasErrors(['client_email', 'items']);

        $this->assertSame(0, Invoice::count());
    }

    public function test_pdf_download_and_preview(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());
        $invoice = Invoice::first();

        $download = $this->actingAs($user)->get(route('admin.invoices.pdf', $invoice));
        $download->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $download->getContent());
        $this->assertStringContainsString('Invoice-INV-2026-0001.pdf', $download->headers->get('content-disposition'));

        $this->actingAs($user)->get(route('admin.invoices.preview', $invoice))
            ->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    public function test_mail_renders_with_pdf_attachment(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());
        $invoice = Invoice::first();

        $mail = new InvoiceMail($invoice);
        $mail->assertSeeInHtml('INV-2026-0001');
        $mail->assertHasSubject('Invoice INV-2026-0001 from '.config('invoice.company.name'));
        $attachments = $mail->attachments();
        $this->assertCount(1, $attachments);
        $attachments[0]->attachWith(
            fn () => $this->fail('Expected data attachment'),
            function ($data, $attachment) {
                $this->assertSame('Invoice-INV-2026-0001.pdf', $attachment->as);
                $this->assertSame('application/pdf', $attachment->mime);
                $this->assertStringStartsWith('%PDF', $data());
            }
        );
    }

    public function test_send_button_and_status_change(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload(['send_email' => '0']));
        $invoice = Invoice::first();

        $this->actingAs($user)->post(route('admin.invoices.send', $invoice))->assertRedirect();
        Mail::assertQueued(InvoiceMail::class);
        $this->assertSame(InvoiceStatus::Sent, $invoice->fresh()->status);

        $this->actingAs($user)->patch(route('admin.invoices.status', $invoice), ['status' => 'paid'])->assertRedirect();
        $this->assertSame(InvoiceStatus::Paid, $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);

        $this->actingAs($user)->get(route('admin.invoices.show', $invoice))->assertOk()->assertSee('INV-2026-0001');
        $this->actingAs($user)->get(route('admin.invoices.edit', $invoice))->assertOk();
    }

    public function test_delete_removes_invoice_and_items(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('admin.invoices.store'), $this->payload());
        $invoice = Invoice::first();

        $this->actingAs($user)->delete(route('admin.invoices.destroy', $invoice))->assertRedirect(route('admin.invoices.index'));
        $this->assertSame(0, Invoice::count());
        $this->assertDatabaseCount('invoice_items', 0);
    }
}
