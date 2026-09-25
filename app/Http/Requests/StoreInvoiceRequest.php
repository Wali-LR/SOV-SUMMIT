<?php

namespace App\Http\Requests;

use App\Enums\InvoiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_company' => ['nullable', 'string', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:60'],
            'client_address' => ['nullable', 'string', 'max:1000'],
            'client_vat_no' => ['nullable', 'string', 'max:60'],

            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', Rule::enum(InvoiceStatus::class)],

            'discount' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'notes' => ['nullable', 'string', 'max:5000'],
            'terms' => ['nullable', 'string', 'max:5000'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.total' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'items.*.vat_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'send_email' => ['sometimes', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.description' => 'item description',
            'items.*.total' => 'amount',
            'items.*.vat_rate' => 'VAT %',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Drop fully empty rows so a blank line doesn't fail validation.
        $items = collect($this->input('items', []))
            ->filter(fn ($row) => is_array($row) && (
                trim((string) ($row['description'] ?? '')) !== ''
                || ($row['total'] ?? '') !== ''
            ))
            ->map(function ($row) {
                $row['vat_rate'] = $row['vat_rate'] === null || $row['vat_rate'] === '' ? 0 : $row['vat_rate'];
                $row['total']    = $row['total']    === null || $row['total']    === '' ? 0 : $row['total'];
                return $row;
            })
            ->values()
            ->all();

        $this->merge([
            'items' => $items,
            'currency' => strtoupper((string) $this->input('currency', config('invoice.currency'))),
            'send_email' => $this->boolean('send_email'),
            'discount' => $this->input('discount') === null || $this->input('discount') === '' ? 0 : $this->input('discount'),
            'tax_rate' => $this->input('tax_rate') === null || $this->input('tax_rate') === '' ? 0 : $this->input('tax_rate'),
        ]);
    }

    /** Invoice columns only (no items / flags). */
    public function invoiceData(): array
    {
        return collect($this->validated())
            ->except(['items', 'send_email'])
            ->all();
    }

    public function items(): array
    {
        return $this->validated('items');
    }

    public function shouldSendEmail(): bool
    {
        return (bool) $this->validated('send_email', false);
    }
}
