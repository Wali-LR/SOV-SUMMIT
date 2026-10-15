<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Services\InvoicePdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    /**
     * @param  bool  $isUpdate  true when re-sent after the invoice was edited
     */
    public function __construct(
        public Invoice $invoice,
        public bool $isUpdate = false,
    ) {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        $company = config('invoice.company.name');
        $subject = $this->isUpdate
            ? "Updated invoice {$this->invoice->number} from {$company}"
            : "Invoice {$this->invoice->number} from {$company}";

        $bcc = config('invoice.bcc') ? [new Address(config('invoice.bcc'))] : [];

        return new Envelope(
            subject: $subject,
            replyTo: [new Address(config('invoice.company.email'), $company)],
            bcc: $bcc,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoice',
            with: [
                'invoice' => $this->invoice->loadMissing('items'),
                'company' => config('invoice.company'),
                'isUpdate' => $this->isUpdate,
            ],
        );
    }

    /** PDF is built at send time, so the queued job always has the latest data. */
    public function attachments(): array
    {
        $invoice = $this->invoice;

        return [
            Attachment::fromData(fn () => InvoicePdf::output($invoice), $invoice->pdfFilename())
                ->withMime('application/pdf'),
        ];
    }
}
