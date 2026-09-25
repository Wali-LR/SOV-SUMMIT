@php use App\Enums\InvoiceStatus; @endphp
<x-admin-layout title="Invoice {{ $invoice->number }}" subtitle="{{ $invoice->client_name }} · {{ $invoice->money($invoice->total) }}">
    <x-slot name="actions">
        <a href="{{ route('admin.invoices.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900 mr-2">Back</a>
        <a href="{{ route('admin.invoices.preview', $invoice) }}" target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
            Preview
        </a>
        <a href="{{ route('admin.invoices.pdf', $invoice) }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            PDF
        </a>
        <a href="{{ route('admin.invoices.edit', $invoice) }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
            Edit
        </a>
        <form action="{{ route('admin.invoices.send', $invoice) }}" method="POST"
              onsubmit="return confirm('Email invoice {{ $invoice->number }} to {{ $invoice->client_email }}?');">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                {{ $invoice->sent_count > 0 ? 'Resend to client' : 'Send to client' }}
            </button>
        </form>
    </x-slot>

    <div class="grid grid-cols-12 gap-6">
        {{-- Invoice preview --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white border border-slate-200 rounded-lg p-6 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-6">
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Bill to</div>
                        <div class="mt-1 font-semibold text-slate-900">{{ $invoice->client_name }}</div>
                        @if ($invoice->client_company) <div class="text-sm text-slate-700">{{ $invoice->client_company }}</div> @endif
                        @if ($invoice->client_address) <div class="text-sm text-slate-600 whitespace-pre-line">{{ $invoice->client_address }}</div> @endif
                        <div class="text-sm text-slate-600">{{ $invoice->client_email }}</div>
                        @if ($invoice->client_phone) <div class="text-sm text-slate-600">{{ $invoice->client_phone }}</div> @endif
                        @if ($invoice->client_vat_no) <div class="text-sm text-slate-600">VAT no. {{ $invoice->client_vat_no }}</div> @endif
                    </div>
                    <div class="text-right text-sm">
                        <div class="font-mono font-semibold text-slate-900">{{ $invoice->number }}</div>
                        <div class="text-slate-600 mt-1">Issued {{ $invoice->issue_date->format('d M Y') }}</div>
                        @if ($invoice->due_date)
                            <div class="{{ $invoice->isOverdue() ? 'text-red-600 font-medium' : 'text-slate-600' }}">
                                Due {{ $invoice->due_date->format('d M Y') }} @if ($invoice->isOverdue()) (overdue) @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                                <th class="py-2 pr-4">Description</th>
                                <th class="py-2 px-4 text-right">Net amount</th>
                                <th class="py-2 px-4 text-right">VAT %</th>
                                <th class="py-2 pl-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td class="py-3 pr-4 text-slate-800 whitespace-pre-line">{{ $item->description }}</td>
                                    <td class="py-3 px-4 text-right tabular-nums">{{ number_format((float) $item->unit_price, 2, '.', "'") }}</td>
                                    <td class="py-3 px-4 text-right tabular-nums">{{ rtrim(rtrim(number_format((float) $item->vat_rate, 2, '.', ''), '0'), '.') }}%</td>
                                    <td class="py-3 pl-4 text-right tabular-nums font-medium">{{ number_format((float) $item->amount, 2, '.', "'") }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    $subNet = (float) $invoice->items->sum('unit_price');
                @endphp
                <div class="mt-6 flex justify-end">
                    <dl class="w-full max-w-xs text-sm space-y-2">
                        <div class="flex justify-between"><dt class="text-slate-500">Sub Total (net)</dt><dd class="tabular-nums">{{ $invoice->money($subNet) }}</dd></div>
                        @if ((float) $invoice->discount > 0)
                            <div class="flex justify-between"><dt class="text-slate-500">Discount</dt><dd class="tabular-nums">− {{ $invoice->money($invoice->discount) }}</dd></div>
                        @endif
                        <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-semibold text-slate-900"><dt>Total Amount</dt><dd class="tabular-nums">{{ $invoice->money($invoice->total) }}</dd></div>
                    </dl>
                </div>

                @if ($invoice->notes || $invoice->terms)
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 pt-6 text-sm">
                        @if ($invoice->notes)
                            <div>
                                <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Notes</div>
                                <p class="mt-1 text-slate-700 whitespace-pre-line">{{ $invoice->notes }}</p>
                            </div>
                        @endif
                        @if ($invoice->terms)
                            <div>
                                <div class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Terms</div>
                                <p class="mt-1 text-slate-600 whitespace-pre-line">{{ $invoice->terms }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <section class="bg-white border border-slate-200 rounded-lg">
                <header class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900">Status</h3>
                </header>
                <div class="p-5 space-y-4 text-sm">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border {{ $invoice->status->badgeClass() }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $invoice->status->dotClass() }}"></span>
                        {{ $invoice->status->label() }}
                    </span>

                    <form action="{{ route('admin.invoices.status', $invoice) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="flex-1 rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm">
                            @foreach (InvoiceStatus::cases() as $case)
                                <option value="{{ $case->value }}" @selected($invoice->status === $case)>{{ $case->label() }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2 text-sm font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Save</button>
                    </form>

                    <dl class="space-y-2 border-t border-slate-100 pt-4">
                        <div class="flex justify-between"><dt class="text-slate-500">Emailed</dt><dd>{{ $invoice->sent_count }} {{ \Illuminate\Support\Str::plural('time', $invoice->sent_count) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Last sent</dt><dd>{{ $invoice->sent_at?->format('d M Y, H:i') ?? '—' }}</dd></div>
                        @if ($invoice->paid_at)
                            <div class="flex justify-between"><dt class="text-slate-500">Paid</dt><dd>{{ $invoice->paid_at->format('d M Y') }}</dd></div>
                        @endif
                        <div class="flex justify-between"><dt class="text-slate-500">Created</dt><dd>{{ $invoice->created_at->format('d M Y') }}</dd></div>
                        @if ($invoice->creator)
                            <div class="flex justify-between"><dt class="text-slate-500">By</dt><dd>{{ $invoice->creator->name }}</dd></div>
                        @endif
                    </dl>
                </div>
            </section>

            <section class="bg-white border border-red-100 rounded-lg p-5">
                <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete invoice {{ $invoice->number }}? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Delete invoice</button>
                </form>
            </section>
        </div>
    </div>
</x-admin-layout>
