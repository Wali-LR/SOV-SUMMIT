<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Sent => 'Sent',
            self::Paid => 'Paid',
            self::Cancelled => 'Cancelled',
        };
    }

    /** Tailwind classes for the admin status badge. */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-600 border-slate-200',
            self::Sent => 'bg-sky-50 text-sky-700 border-sky-100',
            self::Paid => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            self::Cancelled => 'bg-red-50 text-red-700 border-red-100',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-400',
            self::Sent => 'bg-sky-500',
            self::Paid => 'bg-emerald-500',
            self::Cancelled => 'bg-red-500',
        };
    }
}
