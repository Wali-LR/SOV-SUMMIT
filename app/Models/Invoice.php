<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'number',
        'status',
        'customer_id',
        'client_name',
        'client_email',
        'client_company',
        'client_phone',
        'client_address',
        'client_vat_no',
        'issue_date',
        'due_date',
        'currency',
        'subtotal',
        'discount',
        'tax_rate',
        'tax_amount',
        'total',
        'notes',
        'terms',
        'sent_at',
        'sent_count',
        'paid_at',
        'created_by',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'sent_count' => 'integer',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    /* ---------------------------------------------------------------- */
    /* Relations */
    /* ---------------------------------------------------------------- */

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('position')->orderBy('id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /* ---------------------------------------------------------------- */
    /* Numbering */
    /* ---------------------------------------------------------------- */

    /**
     * Next number in the form PREFIX-YYYY-0001. Call inside a DB transaction.
     */
    public static function nextNumber(?int $year = null): string
    {
        $year = $year ?? (int) now()->format('Y');
        $prefix = config('invoice.prefix', 'INV').'-'.$year.'-';

        $last = static::where('number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('number')
            ->value('number');

        $seq = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    /* ---------------------------------------------------------------- */
    /* Totals */
    /* ---------------------------------------------------------------- */

    /**
     * Replace all line items and recalculate totals.
     *
     * @param  array<int, array{description:string, quantity:float|string, unit_price:float|string}>  $items
     */
    public function syncItems(array $items): void
    {
        $this->items()->delete();

        foreach (array_values($items) as $i => $row) {
            $gross   = round((float) ($row['total'] ?? 0), 2);
            $vatRate = round((float) ($row['vat_rate'] ?? 0), 2);
            $net     = $vatRate > 0
                ? round($gross * 100 / (100 + $vatRate), 2)
                : $gross;

            $this->items()->create([
                'description' => $row['description'],
                'quantity'    => 1,
                'unit_price'  => $net,   // Net amount (VAT-extracted)
                'vat_rate'    => $vatRate,
                'amount'      => $gross, // Gross total (VAT-inclusive)
                'position'    => $i,
            ]);
        }

        $this->unsetRelation('items');
        $this->recalculate();
    }

    public function recalculate(): void
    {
        $items = $this->items()->get();

        $subGross = round((float) $items->sum('amount'), 2);         // Σ gross (spec Sub Total)
        $subNet   = round((float) $items->sum('unit_price'), 2);      // Σ net
        $discount = max(0.0, min(round((float) $this->discount, 2), $subGross));

        $this->forceFill([
            'subtotal'   => $subNet,               // stored as net (for reporting)
            'discount'   => $discount,
            'tax_rate'   => 0,
            'tax_amount' => round($subGross - $subNet, 2),
            'total'      => round($subGross - $discount, 2),
        ])->save();
    }

    /* ---------------------------------------------------------------- */
    /* Helpers */
    /* ---------------------------------------------------------------- */

    public function money(float|string|null $value): string
    {
        return $this->currency.' '.number_format((float) $value, 2, '.', "'");
    }

    public function pdfFilename(): string
    {
        return 'Invoice-'.$this->number.'.pdf';
    }

    public function isOverdue(): bool
    {
        return $this->status === InvoiceStatus::Sent
            && $this->due_date
            && $this->due_date->isPast()
            && ! $this->due_date->isToday();
    }

    public function markAsSent(): void
    {
        $this->forceFill([
            'status' => $this->status === InvoiceStatus::Draft ? InvoiceStatus::Sent : $this->status,
            'sent_at' => now(),
            'sent_count' => $this->sent_count + 1,
        ])->save();
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('number', 'like', "%{$term}%")
                ->orWhere('client_name', 'like', "%{$term}%")
                ->orWhere('client_email', 'like', "%{$term}%")
                ->orWhere('client_company', 'like', "%{$term}%");
        });
    }
}
