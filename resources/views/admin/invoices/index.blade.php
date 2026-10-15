@php use App\Enums\InvoiceStatus; @endphp
<x-admin-layout title="Invoices" subtitle="{{ $invoices->total() }} {{ \Illuminate\Support\Str::plural('invoice', $invoices->total()) }}">
    <x-slot name="actions">
        <a href="{{ route('admin.invoices.create') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            New invoice
        </a>
    </x-slot>

    {{-- Summary cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach (InvoiceStatus::cases() as $case)
            @php $row = $totals->get($case->value); @endphp
            <a href="{{ route('admin.invoices.index', ['status' => $case->value]) }}"
               class="bg-white border rounded-lg p-4 hover:border-slate-400 {{ $status === $case->value ? 'border-slate-900 ring-1 ring-slate-900' : 'border-slate-200' }}">
                <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                    <span class="w-1.5 h-1.5 rounded-full {{ $case->dotClass() }}"></span>
                    {{ $case->label() }}
                </div>
                <div class="mt-2 text-xl font-semibold text-slate-900 tabular-nums">{{ (int) ($row->count ?? 0) }}</div>
                <div class="text-xs text-slate-500 tabular-nums">{{ number_format((float) ($row->amount ?? 0), 2, '.', "'") }}</div>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.invoices.index') }}" class="flex flex-wrap items-center gap-2 mb-4">
        <input type="search" name="q" value="{{ $search }}" placeholder="Search number, client, email…"
               class="w-full sm:w-72 rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm">
        <select name="status" class="rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm">
            <option value="">All statuses</option>
            @foreach (InvoiceStatus::cases() as $case)
                <option value="{{ $case->value }}" @selected($status === $case->value)>{{ $case->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3.5 py-2 text-sm font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">Filter</button>
        @if ($search || $status)
            <a href="{{ route('admin.invoices.index') }}" class="text-sm text-slate-500 hover:text-slate-900">Clear</a>
        @endif
    </form>

    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
        @if ($invoices->isEmpty())
            <div class="p-10 text-center">
                <p class="text-slate-500 text-sm">No invoices found.</p>
                <a href="{{ route('admin.invoices.create') }}" class="inline-block mt-3 text-sm font-medium text-slate-900 underline">Create your first invoice</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-[11px] font-semibold uppercase tracking-widest text-slate-500">
                            <th class="px-4 py-3">Number</th>
                            <th class="px-4 py-3">Client</th>
                            <th class="px-4 py-3">Issued</th>
                            <th class="px-4 py-3">Due</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($invoices as $invoice)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="font-mono font-medium text-slate-900 hover:underline">{{ $invoice->number }}</a>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $invoice->client_name }}</div>
                                    <div class="text-xs text-slate-500">{{ $invoice->client_company ?: $invoice->client_email }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-700">{{ $invoice->issue_date->format('d M Y') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap {{ $invoice->isOverdue() ? 'text-red-600 font-medium' : 'text-slate-700' }}">
                                    {{ $invoice->due_date?->format('d M Y') ?? '—' }}
                                    @if ($invoice->isOverdue()) <span class="text-[11px]">(overdue)</span> @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right font-medium text-slate-900 tabular-nums">{{ $invoice->money($invoice->total) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $invoice->status->badgeClass() }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $invoice->status->dotClass() }}"></span>
                                        {{ $invoice->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="text-slate-500 hover:text-slate-900 text-xs mr-3">PDF</a>
                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="text-slate-900 hover:underline text-xs font-medium mr-3">Edit</a>
                                    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Delete invoice {{ $invoice->number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($invoices->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-white">
                    {{ $invoices->links() }}
                </div>
            @endif
        @endif
    </div>
</x-admin-layout>
