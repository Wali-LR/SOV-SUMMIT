<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        @page { margin: 36px 42px 60px 42px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; line-height: 1.45; margin: 0; }
        .muted { color: #64748b; }
        .small { font-size: 9px; }
        .right { text-align: right; }
        .nowrap { white-space: nowrap; }
        table { width: 100%; border-collapse: collapse; }

        .brand { font-size: 15px; font-weight: bold; letter-spacing: 3px; color: #0f172a; }
        .title { font-size: 26px; font-weight: bold; color: #0f172a; letter-spacing: 1px; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .status-draft { background: #f1f5f9; color: #475569; }
        .status-sent { background: #e0f2fe; color: #0369a1; }
        .status-paid { background: #d1fae5; color: #047857; }
        .status-cancelled { background: #fee2e2; color: #b91c1c; }

        .label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8; margin-bottom: 4px; }
        .box td { vertical-align: top; }

        .items { margin-top: 24px; }
        .items th { background: #0f172a; color: #fff; font-size: 8px; text-transform: uppercase; letter-spacing: 1.2px; padding: 8px 10px; text-align: left; }
        .items th.right { text-align: right; }
        .items td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .items tr:nth-child(even) td { background: #f8fafc; }

        .totals { width: 46%; margin-left: 54%; margin-top: 14px; }
        .totals td { padding: 5px 10px; }
        .totals .grand td { border-top: 2px solid #0f172a; font-size: 13px; font-weight: bold; color: #0f172a; padding-top: 8px; }

        .section { margin-top: 26px; }
        .paid-stamp { position: absolute; top: 210px; right: 40px; border: 3px solid #059669; color: #059669; font-size: 28px; font-weight: bold; padding: 6px 18px; letter-spacing: 4px; transform: rotate(-12deg); opacity: 0.5; }

        footer { position: fixed; bottom: -40px; left: 0; right: 0; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px; }
    </style>
</head>
<body>
    @php
        $taxLabel = config('invoice.tax_label', 'VAT');
        $statusValue = $invoice->status->value;
    @endphp

    <footer>
        {{ $company['name'] }}
        @if (!empty($company['website'])) · {{ $company['website'] }} @endif
        @if (!empty($company['email'])) · {{ $company['email'] }} @endif
        @if (!empty($company['vat_no'])) · VAT {{ $company['vat_no'] }} @endif
    </footer>

    @if ($statusValue === 'paid')
        <div class="paid-stamp">PAID</div>
    @endif

    {{-- Header --}}
    <table class="box">
        <tr>
            <td style="width: 55%;">
                @if ($logo)
                    <img src="{{ $logo }}" alt="" style="max-height: 48px; max-width: 200px; margin-bottom: 8px;"><br>
                @else
                    <div class="brand">SOV SUMMIT</div>
                @endif
                <div style="margin-top: 6px;">
                    <strong>{{ $company['name'] }}</strong><br>
                    {!! nl2br(e($company['address'])) !!}<br>
                    @if (!empty($company['email'])) {{ $company['email'] }}<br> @endif
                    @if (!empty($company['phone'])) {{ $company['phone'] }}<br> @endif
                    @if (!empty($company['vat_no'])) <span class="muted">VAT no.</span> {{ $company['vat_no'] }} @endif
                </div>
            </td>
            <td class="right" style="width: 45%;">
                <div class="title">INVOICE</div>
                <div style="margin-top: 4px;"><span class="status status-{{ $statusValue }}">{{ $invoice->status->label() }}</span></div>
                <table style="margin-top: 12px; width: auto; margin-left: auto;">
                    <tr><td class="muted right" style="padding: 2px 10px 2px 0;">Invoice no.</td><td class="right nowrap"><strong>{{ $invoice->number }}</strong></td></tr>
                    <tr><td class="muted right" style="padding: 2px 10px 2px 0;">Issue date</td><td class="right nowrap">{{ $invoice->issue_date->format('d.m.Y') }}</td></tr>
                    @if ($invoice->due_date)
                        <tr><td class="muted right" style="padding: 2px 10px 2px 0;">Due date</td><td class="right nowrap">{{ $invoice->due_date->format('d.m.Y') }}</td></tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    {{-- Bill to --}}
    <table class="box section">
        <tr>
            <td style="width: 55%;">
                <div class="label">Bill to</div>
                <strong>{{ $invoice->client_name }}</strong><br>
                @if ($invoice->client_company) {{ $invoice->client_company }}<br> @endif
                @if ($invoice->client_address) {!! nl2br(e($invoice->client_address)) !!}<br> @endif
                {{ $invoice->client_email }}
                @if ($invoice->client_phone) <br>{{ $invoice->client_phone }} @endif
                @if ($invoice->client_vat_no) <br><span class="muted">VAT no.</span> {{ $invoice->client_vat_no }} @endif
            </td>
            <td class="right" style="width: 45%;">
                <div class="label">Amount due</div>
                <div style="font-size: 20px; font-weight: bold; color: #0f172a;">{{ $invoice->money($invoice->total) }}</div>
                @if ($invoice->due_date && $statusValue !== 'paid')
                    <div class="muted">by {{ $invoice->due_date->format('d F Y') }}</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- Items --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 51%;">Description</th>
                <th class="right" style="width: 16%;">Net amount</th>
                <th class="right" style="width: 10%;">VAT %</th>
                <th class="right" style="width: 18%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $i => $item)
                <tr>
                    <td class="muted">{{ $i + 1 }}</td>
                    <td>{!! nl2br(e($item->description)) !!}</td>
                    <td class="right nowrap">{{ number_format((float) $item->unit_price, 2, '.', "'") }}</td>
                    <td class="right nowrap">{{ rtrim(rtrim(number_format((float) $item->vat_rate, 2, '.', ''), '0'), '.') }}%</td>
                    <td class="right nowrap">{{ number_format((float) $item->amount, 2, '.', "'") }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    @php
        $subNet = (float) $invoice->items->sum('unit_price');
    @endphp
    <table class="totals">
        <tr><td class="muted">Sub Total (net)</td><td class="right nowrap">{{ $invoice->money($subNet) }}</td></tr>
        @if ((float) $invoice->discount > 0)
            <tr><td class="muted">Discount</td><td class="right nowrap">− {{ $invoice->money($invoice->discount) }}</td></tr>
        @endif
        <tr class="grand"><td>Total Amount</td><td class="right nowrap">{{ $invoice->money($invoice->total) }}</td></tr>
    </table>

    {{-- Payment details --}}
    @if (!empty($bank['iban']))
        <div class="section">
            <div class="label">Payment details</div>
            <table style="width: auto;">
                @if (!empty($bank['name'])) <tr><td class="muted" style="padding: 1px 14px 1px 0;">Bank</td><td>{{ $bank['name'] }}</td></tr> @endif
                <tr><td class="muted" style="padding: 1px 14px 1px 0;">IBAN</td><td>{{ $bank['iban'] }}</td></tr>
                @if (!empty($bank['swift'])) <tr><td class="muted" style="padding: 1px 14px 1px 0;">BIC / SWIFT</td><td>{{ $bank['swift'] }}</td></tr> @endif
                <tr><td class="muted" style="padding: 1px 14px 1px 0;">Reference</td><td>{{ $invoice->number }}</td></tr>
            </table>
        </div>
    @endif

    @if ($invoice->notes)
        <div class="section">
            <div class="label">Notes</div>
            <div>{!! nl2br(e($invoice->notes)) !!}</div>
        </div>
    @endif

    @if ($invoice->terms)
        <div class="section">
            <div class="label">Terms</div>
            <div class="muted">{!! nl2br(e($invoice->terms)) !!}</div>
        </div>
    @endif
</body>
</html>
