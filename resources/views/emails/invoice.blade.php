<x-mail::message>
# {{ $isUpdate ? 'Updated invoice' : 'Invoice' }} {{ $invoice->number }}

Dear {{ $invoice->client_name }},

@if ($isUpdate)
Please find attached the **updated** invoice {{ $invoice->number }}. It replaces the version we sent before.
@else
Thank you for working with {{ $company['name'] }}. Please find your invoice attached as a PDF.
@endif

<x-mail::table>
| | |
|:--|--:|
| **Invoice no.** | {{ $invoice->number }} |
| **Issue date** | {{ $invoice->issue_date->format('d.m.Y') }} |
@if ($invoice->due_date)
| **Due date** | {{ $invoice->due_date->format('d.m.Y') }} |
@endif
| **Amount due** | **{{ $invoice->money($invoice->total) }}** |
</x-mail::table>

@if ($invoice->notes)
{{ $invoice->notes }}
@endif

If you have any questions, just reply to this email.

Kind regards,<br>
{{ $company['name'] }}
</x-mail::message>
