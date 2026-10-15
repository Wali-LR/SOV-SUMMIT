<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Services\InvoicePdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q', ''));

        $invoices = Invoice::query()
            ->search($search)
            ->when($status && InvoiceStatus::tryFrom($status), fn ($q) => $q->where('status', $status))
            ->latest('issue_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $totals = Invoice::query()
            ->selectRaw('status, COUNT(*) as count, SUM(total) as amount')
            ->groupBy('status')
            ->get()
            ->keyBy(fn ($row) => $row->status instanceof InvoiceStatus ? $row->status->value : $row->status);

        return view('admin.invoices.index', compact('invoices', 'status', 'search', 'totals'));
    }

    public function create(): View
    {
        $invoice = new Invoice([
            'issue_date' => now(),
            'due_date' => now()->addDays(config('invoice.due_days', 30)),
            'currency' => config('invoice.currency', 'CHF'),
            'tax_rate' => config('invoice.tax_rate', 0),
            'discount' => 0,
            'terms' => config('invoice.default_terms'),
            'status' => InvoiceStatus::Draft,
        ]);

        return view('admin.invoices.create', compact('invoice'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $invoice = DB::transaction(function () use ($request) {
            $invoice = new Invoice($request->invoiceData());
            $invoice->number = Invoice::nextNumber($request->date('issue_date')?->year);
            $invoice->created_by = $request->user()->id;
            $this->applyStatusTimestamps($invoice);
            $invoice->save();

            $invoice->syncItems($request->items());

            return $invoice;
        });

        $message = "Invoice {$invoice->number} created.";

        if ($request->shouldSendEmail()) {
            $this->sendToClient($invoice, isUpdate: false);
            $message .= " Emailed to {$invoice->client_email}.";
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('status', $message);
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('items', 'creator');

        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(StoreInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        DB::transaction(function () use ($request, $invoice) {
            $invoice->fill($request->invoiceData());
            $this->applyStatusTimestamps($invoice);
            $invoice->save();

            $invoice->syncItems($request->items());
        });

        $message = "Invoice {$invoice->number} updated.";

        if ($request->shouldSendEmail()) {
            $this->sendToClient($invoice, isUpdate: $invoice->sent_count > 0);
            $message .= " Updated invoice emailed to {$invoice->client_email}.";
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('status', $message);
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $number = $invoice->number;
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('status', "Invoice {$number} deleted.");
    }

    /** Download the PDF. */
    public function pdf(Invoice $invoice): Response
    {
        return InvoicePdf::make($invoice)->download($invoice->pdfFilename());
    }

    /** Open the PDF inline in the browser. */
    public function preview(Invoice $invoice): Response
    {
        return InvoicePdf::make($invoice)->stream($invoice->pdfFilename());
    }

    /** Manual "Send to client" button. */
    public function send(Invoice $invoice): RedirectResponse
    {
        $this->sendToClient($invoice, isUpdate: $invoice->sent_count > 0);

        return back()->with('status', "Invoice {$invoice->number} emailed to {$invoice->client_email}.");
    }

    /** Quick status change from the show page. */
    public function status(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(InvoiceStatus::class)],
        ]);

        $invoice->status = InvoiceStatus::from($data['status']);
        $this->applyStatusTimestamps($invoice);
        $invoice->save();

        return back()->with('status', "Invoice {$invoice->number} marked as {$invoice->status->label()}.");
    }

    private function sendToClient(Invoice $invoice, bool $isUpdate): void
    {
        Mail::to($invoice->client_email, $invoice->client_name)
            ->queue(new InvoiceMail($invoice, $isUpdate));

        $invoice->markAsSent();
    }

    private function applyStatusTimestamps(Invoice $invoice): void
    {
        if ($invoice->status === InvoiceStatus::Paid && ! $invoice->paid_at) {
            $invoice->paid_at = now();
        } elseif ($invoice->status !== InvoiceStatus::Paid) {
            $invoice->paid_at = null;
        }
    }
}
