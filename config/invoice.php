<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Invoice settings
    |--------------------------------------------------------------------------
    | Company details printed on every invoice PDF and email.
    | Override any value from .env.
    */

    'prefix' => env('INVOICE_PREFIX', 'INV'),
    'currency' => env('INVOICE_CURRENCY', 'CHF'),
    'tax_rate' => (float) env('INVOICE_TAX_RATE', 8.1),
    'tax_label' => env('INVOICE_TAX_LABEL', 'VAT'),
    'due_days' => (int) env('INVOICE_DUE_DAYS', 30),

    // BCC a copy of every invoice email to this address (leave empty to disable).
    'bcc' => env('INVOICE_BCC'),

    'company' => [
        'name' => env('INVOICE_COMPANY_NAME', 'Sovereign Summit GmbH'),
        'address' => env('INVOICE_COMPANY_ADDRESS', "Bahnhofstrasse 21\n6300 Zug, Switzerland"),
        'email' => env('INVOICE_COMPANY_EMAIL', 'info@sov-summit.com'),
        'phone' => env('INVOICE_COMPANY_PHONE', '+41 79 876 35 73'),
        'website' => env('INVOICE_COMPANY_WEBSITE', 'sov-summit.com'),
        'vat_no' => env('INVOICE_COMPANY_VAT_NO'),
        // Relative to /public — PNG or JPG works best with DomPDF.
        'logo' => env('INVOICE_COMPANY_LOGO'),
    ],

    'bank' => [
        'name' => env('INVOICE_BANK_NAME'),
        'iban' => env('INVOICE_BANK_IBAN'),
        'swift' => env('INVOICE_BANK_SWIFT'),
    ],

    'default_terms' => env('INVOICE_DEFAULT_TERMS', 'Payment is due within 30 days of the invoice date. Please include the invoice number as the payment reference.'),
];
