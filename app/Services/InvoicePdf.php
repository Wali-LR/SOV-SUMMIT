<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;

class InvoicePdf
{
    public static function make(Invoice $invoice): DomPdf
    {
        $invoice->loadMissing('items');

        return Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'company' => config('invoice.company'),
            'bank' => config('invoice.bank'),
            'logo' => static::logoDataUri(),
        ])
            ->setPaper('a4')
            ->setOption([
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
            ]);
    }

    public static function output(Invoice $invoice): string
    {
        return static::make($invoice)->output();
    }

    /** Embed the logo as base64 so DomPDF never needs remote access. */
    private static function logoDataUri(): ?string
    {
        $logo = config('invoice.company.logo');
        if (! $logo) {
            return null;
        }

        $path = public_path(ltrim($logo, '/'));
        if (! is_file($path)) {
            return null;
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
    }
}
