@csrf
@push('head')
<style>[x-cloak] { display: none !important; }</style>
@endpush
@php
    use App\Enums\InvoiceStatus;

    $inputClass = 'mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm';
    $labelClass = 'block text-sm font-medium text-slate-700';
    $errorClass = 'mt-1 text-xs text-red-600';

    $initialItems = old('items')
        ?? ($invoice->exists
            ? $invoice->items->map(fn ($i) => [
                'description' => $i->description,
                'total'       => (float) $i->amount,
                'vat_rate'    => (float) $i->vat_rate,
            ])->values()->all()
            : [['description' => '', 'total' => '', 'vat_rate' => (float) config('invoice.tax_rate', 0)]]);

    $formState = [
        'items'    => array_values($initialItems),
        'discount' => (float) old('discount', $invoice->discount ?? 0),
        'defaultVat' => (float) config('invoice.tax_rate', 0),
        'currency' => old('currency', $invoice->currency),
        'client'   => [
            'name'    => old('client_name',    $invoice->client_name ?? ''),
            'email'   => old('client_email',   $invoice->client_email ?? ''),
            'company' => old('client_company', $invoice->client_company ?? ''),
            'phone'   => old('client_phone',   $invoice->client_phone ?? ''),
            'address' => old('client_address', $invoice->client_address ?? ''),
            'vat_no'  => old('client_vat_no',  $invoice->client_vat_no ?? ''),
        ],
        'customerId' => old('customer_id', $invoice->customer_id ?? null),
    ];

    $customerRoutes = [
        'search' => route('admin.customers.search'),
        'store'  => route('admin.customers.store'),
    ];
@endphp

<div x-data="invoiceForm(@js($formState), @js($customerRoutes))" class="grid grid-cols-12 gap-6">

    {{-- Bill-to (left) --}}
    <div class="col-span-12 lg:col-span-8">
        <section class="bg-white border border-slate-200 rounded-lg">
            <input type="hidden" name="customer_id" :value="customerId ?? ''">
            <header class="px-5 py-4 border-b border-slate-100 flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Bill to</h3>
                        <p class="text-xs text-slate-500 mt-0.5">The invoice is emailed to this address.</p>
                    </div>
                    <template x-if="customerId">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-medium border border-emerald-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Linked to customer
                        </span>
                    </template>
                </div>
                <div class="flex items-center gap-2" x-show="customerId" x-cloak>
                    <button type="button" @click="openPicker()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        Change
                    </button>
                    <button type="button" @click="clearCustomer()"
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-md text-slate-500 hover:text-red-600 hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        Unlink
                    </button>
                </div>
            </header>
            {{-- Hidden inputs carry the client data on submit --}}
            <input type="hidden" name="client_name"    :value="client.name">
            <input type="hidden" name="client_email"   :value="client.email">
            <input type="hidden" name="client_company" :value="client.company">
            <input type="hidden" name="client_phone"   :value="client.phone">
            <input type="hidden" name="client_address" :value="client.address">
            <input type="hidden" name="client_vat_no"  :value="client.vat_no">

            <div class="p-5">
                {{-- Empty state --}}
                <div x-show="!customerId" x-cloak
                     class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-white border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-medium text-slate-900">No customer selected</p>
                    <p class="mt-1 text-xs text-slate-500">Pick an existing customer or add a new one to bill.</p>
                    <button type="button" @click="openPicker()"
                            class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                        Select customer
                    </button>
                    @error('client_name') <p class="{{ $errorClass }} mt-3">{{ $message }}</p> @enderror
                    @error('client_email') <p class="{{ $errorClass }} mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Selected customer summary --}}
                <div x-show="customerId" x-cloak
                     class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex items-start gap-4">
                        <div class="h-11 w-11 rounded-full bg-slate-900 text-white flex items-center justify-center text-sm font-semibold shrink-0"
                             x-text="(client.name || '?').slice(0,1).toUpperCase()"></div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900 truncate" x-text="client.name"></div>
                                    <div class="text-xs text-slate-500 truncate" x-text="client.email"></div>
                                    <template x-if="client.company">
                                        <div class="text-xs text-slate-500 truncate mt-0.5" x-text="client.company"></div>
                                    </template>
                                </div>
                            </div>

                            <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                                <template x-if="client.phone">
                                    <div>
                                        <dt class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Phone</dt>
                                        <dd class="mt-0.5 text-slate-800" x-text="client.phone"></dd>
                                    </div>
                                </template>
                                <template x-if="client.vat_no">
                                    <div>
                                        <dt class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">VAT / UID</dt>
                                        <dd class="mt-0.5 text-slate-800" x-text="client.vat_no"></dd>
                                    </div>
                                </template>
                                <template x-if="client.address">
                                    <div class="sm:col-span-2">
                                        <dt class="text-[11px] font-semibold uppercase tracking-widest text-slate-400">Address</dt>
                                        <dd class="mt-0.5 text-slate-800 whitespace-pre-line" x-text="client.address"></dd>
                                    </div>
                                </template>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- SIDE COLUMN (right) --}}
    <div class="col-span-12 lg:col-span-4 space-y-6">
        <section class="bg-white border border-slate-200 rounded-lg lg:sticky lg:top-24">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Invoice</h3>
                @if ($invoice->exists)
                    <p class="text-xs text-slate-500 mt-0.5 font-mono">{{ $invoice->number }}</p>
                @else
                    <p class="text-xs text-slate-500 mt-0.5">Number is assigned on save.</p>
                @endif
            </header>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="issue_date" class="{{ $labelClass }}">Issue date</label>
                        <input id="issue_date" name="issue_date" type="date" required
                               value="{{ old('issue_date', $invoice->issue_date?->format('Y-m-d')) }}" class="{{ $inputClass }}">
                        @error('issue_date') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="due_date" class="{{ $labelClass }}">Due date</label>
                        <input id="due_date" name="due_date" type="date"
                               value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}" class="{{ $inputClass }}">
                        @error('due_date') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="currency" class="{{ $labelClass }}">Currency</label>
                        <select id="currency" name="currency" x-model="currency" class="{{ $inputClass }}">
                            @foreach (['CHF', 'EUR', 'USD', 'GBP'] as $cur)
                                <option value="{{ $cur }}">{{ $cur }}</option>
                            @endforeach
                        </select>
                        @error('currency') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="status" class="{{ $labelClass }}">Status</label>
                        @php $currentStatus = old('status', $invoice->status?->value ?? 'draft'); @endphp
                        <select id="status" name="status" class="{{ $inputClass }}">
                            @foreach (InvoiceStatus::cases() as $case)
                                <option value="{{ $case->value }}" @selected($currentStatus === $case->value)>{{ $case->label() }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>

                <input type="hidden" name="send_email" value="0">

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md">
                        {{ $invoice->exists ? 'Update invoice' : 'Create invoice' }}
                    </button>
                    <a href="{{ $invoice->exists ? route('admin.invoices.show', $invoice) : route('admin.invoices.index') }}"
                       class="px-4 py-2 text-sm text-slate-600 hover:text-slate-900">Cancel</a>
                </div>
            </div>
        </section>
    </div>

    {{-- Line items (full width) --}}
    <div class="col-span-12">
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Line items</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Totals update as you type.</p>
                </div>
                <button type="button" @click="addItem()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add line
                </button>
            </header>

            <div class="p-5">
                @error('items') <p class="{{ $errorClass }} mb-3">{{ $message }}</p> @enderror

                <div class="hidden md:grid grid-cols-12 gap-3 text-[11px] font-semibold uppercase tracking-widest text-slate-500 pb-3 border-b border-slate-100">
                    <div class="col-span-5">Description</div>
                    <div class="col-span-2 text-right">Net amount</div>
                    <div class="col-span-2 text-right">VAT %</div>
                    <div class="col-span-2 text-right">Amount</div>
                    <div class="col-span-1"></div>
                </div>

                <div class="divide-y divide-slate-200">
                    <template x-for="(item, index) in items" :key="item._key">
                        <div class="grid grid-cols-12 gap-3 items-start py-4 first:pt-4 last:pb-0">
                            <div class="col-span-12 md:col-span-5">
                                <textarea :name="`items[${index}][description]`" x-model="item.description" rows="1" required
                                          placeholder="Service or product"
                                          class="block w-full rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm"></textarea>
                            </div>
                            {{-- Net amount (read-only, auto) --}}
                            <div class="col-span-4 md:col-span-2">
                                <div class="relative">
                                    <input type="text" readonly tabindex="-1"
                                           :value="fmt(lineNet(item))"
                                           class="block w-full pl-2 pr-10 rounded-md border-slate-200 bg-slate-50 text-slate-700 text-sm text-right tabular-nums cursor-not-allowed focus:ring-0 focus:border-slate-200">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[11px] font-medium text-slate-400 tabular-nums" x-text="currency"></span>
                                </div>
                            </div>
                            {{-- VAT % (input) --}}
                            <div class="col-span-4 md:col-span-2">
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" max="100" :name="`items[${index}][vat_rate]`" x-model.number="item.vat_rate"
                                           placeholder="0"
                                           class="block w-full pl-2 pr-7 rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm text-right">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[11px] font-medium text-slate-400">%</span>
                                </div>
                            </div>
                            {{-- Amount / gross (input, VAT-inclusive) --}}
                            <div class="col-span-4 md:col-span-2">
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" :name="`items[${index}][total]`" x-model.number="item.total" required
                                           placeholder="0.00"
                                           class="block w-full pl-2 pr-10 rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm text-right font-medium">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[11px] font-medium text-slate-400 tabular-nums" x-text="currency"></span>
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-1 flex items-center justify-end pt-2 md:pt-0 md:pt-2.5">
                                <button type="button" @click="removeItem(index)" :disabled="items.length === 1"
                                        class="text-slate-400 hover:text-red-600 disabled:opacity-30 disabled:cursor-not-allowed" title="Remove line">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                @foreach ($errors->get('items.*') as $messages)
                    @foreach ($messages as $message)
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @endforeach
                @endforeach

                {{-- Totals --}}
                <div class="mt-6 border-t border-slate-100 pt-4 flex justify-end">
                    <dl class="w-full max-w-xs text-sm space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">Sub Total</dt>
                            <dd class="font-medium tabular-nums" x-text="fmt(subtotal())"></dd>
                        </div>
                        <div class="flex justify-between items-center gap-3">
                            <dt class="text-slate-500">Discount</dt>
                            <dd><input type="number" step="0.01" min="0" name="discount" x-model.number="discount"
                                       class="w-28 rounded-md border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm text-right py-1"></dd>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 pt-2 text-base">
                            <dt class="font-semibold text-slate-900">Total Amount</dt>
                            <dd class="font-semibold text-slate-900 tabular-nums" x-text="currency + ' ' + fmt(total())"></dd>
                        </div>
                    </dl>
                </div>
                @error('discount') <p class="{{ $errorClass }} text-right">{{ $message }}</p> @enderror
            </div>
        </section>
    </div>

    {{-- Notes & terms (full width) --}}
    <div class="col-span-12">
        <section class="bg-white border border-slate-200 rounded-lg">
            <header class="px-5 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Notes &amp; terms</h3>
                <p class="text-xs text-slate-500 mt-0.5">Printed at the bottom of the PDF.</p>
            </header>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="notes" class="{{ $labelClass }}">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="{{ $inputClass }}" placeholder="Thank you for your business.">{{ old('notes', $invoice->notes) }}</textarea>
                    @error('notes') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="terms" class="{{ $labelClass }}">Payment terms</label>
                    <textarea id="terms" name="terms" rows="4" class="{{ $inputClass }}">{{ old('terms', $invoice->terms) }}</textarea>
                    @error('terms') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>
    </div>

    {{-- Customer picker modal --}}
    <template x-teleport="body">
        <div x-show="picker.open" x-cloak
             class="fixed inset-0 z-[60] overflow-y-auto"
             @keydown.escape.window="picker.open && closePicker()"
             x-transition.opacity>
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closePicker()"></div>

            {{-- Dialog --}}
            <div class="relative min-h-full flex items-start justify-center p-4 sm:p-6">
                <div class="relative w-full max-w-lg mt-10 sm:mt-16 bg-white rounded-2xl shadow-2xl ring-1 ring-slate-900/5 overflow-hidden"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    {{-- Header --}}
                    <div class="px-6 pt-5 pb-4 border-b border-slate-100">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-slate-900 text-white flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-slate-900 leading-tight">Select a customer</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Pick from your saved customers or add a new one.</p>
                                </div>
                            </div>
                            <button type="button" @click="closePicker()"
                                    class="text-slate-400 hover:text-slate-900 rounded-md p-1 hover:bg-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>

                        {{-- Tabs --}}
                        <div class="mt-4 grid grid-cols-2 gap-1 p-1 rounded-lg bg-slate-100">
                            <button type="button" @click="picker.mode = 'search'"
                                    :class="picker.mode === 'search' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                                Search existing
                            </button>
                            <button type="button" @click="picker.mode = 'new'; picker.errors = {}"
                                    :class="picker.mode === 'new' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                                Add new
                            </button>
                        </div>
                    </div>

                    {{-- Search mode --}}
                    <div x-show="picker.mode === 'search'">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <div class="relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                                <input id="customerPickerSearch" type="text" x-model="picker.query" @input="onQueryInput()"
                                       placeholder="Search by name, email or company"
                                       class="w-full pl-9 pr-3 py-2.5 rounded-lg border-slate-300 shadow-sm focus:border-slate-900 focus:ring-slate-900 text-sm">
                            </div>
                        </div>

                        <div class="max-h-[380px] overflow-y-auto px-2 py-2">
                            <template x-if="picker.loading">
                                <div class="flex items-center justify-center gap-2 py-10 text-sm text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                    Searching…
                                </div>
                            </template>
                            <template x-if="!picker.loading && picker.results.length === 0">
                                <div class="text-center py-10 px-6">
                                    <div class="mx-auto h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <p class="mt-3 text-sm font-medium text-slate-900">No customers found</p>
                                    <p class="mt-1 text-xs text-slate-500">Try a different search, or add a new customer.</p>
                                    <button type="button" @click="picker.mode = 'new'"
                                            class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-slate-900 text-white hover:bg-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                                        Add new customer
                                    </button>
                                </div>
                            </template>
                            <ul class="space-y-1">
                                <template x-for="c in picker.results" :key="c.id">
                                    <li>
                                        <button type="button" @click="selectCustomer(c)"
                                                class="w-full text-left px-3 py-2.5 rounded-lg hover:bg-slate-50 flex items-center gap-3 group focus:outline-none focus:bg-slate-50">
                                            <div class="h-9 w-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-semibold shrink-0 group-hover:bg-slate-900 group-hover:text-white transition"
                                                 x-text="(c.name || '?').slice(0,1).toUpperCase()"></div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-sm font-medium text-slate-900 truncate" x-text="c.name"></div>
                                                <div class="text-xs text-slate-500 truncate" x-text="c.email"></div>
                                            </div>
                                            <div class="text-xs text-slate-500 truncate max-w-[40%] hidden sm:block" x-text="c.company || ''"></div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 group-hover:text-slate-900 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                        </button>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    {{-- New customer mode --}}
                    <div x-show="picker.mode === 'new'">
                        <div class="p-6 max-h-[420px] overflow-y-auto">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="{{ $labelClass }}">Name <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="picker.newCustomer.name" placeholder="e.g. Sarah Meier"
                                           :class="picker.errors.name ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : ''"
                                           class="{{ $inputClass }}">
                                    <template x-if="picker.errors.name"><p class="{{ $errorClass }}" x-text="picker.errors.name[0]"></p></template>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $labelClass }}">Email <span class="text-red-500">*</span></label>
                                    <input type="email" x-model="picker.newCustomer.email" placeholder="name@company.com"
                                           :class="picker.errors.email ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : ''"
                                           class="{{ $inputClass }}">
                                    <template x-if="picker.errors.email"><p class="{{ $errorClass }}" x-text="picker.errors.email[0]"></p></template>
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Company</label>
                                    <input type="text" x-model="picker.newCustomer.company" class="{{ $inputClass }}">
                                </div>
                                <div>
                                    <label class="{{ $labelClass }}">Phone</label>
                                    <input type="text" x-model="picker.newCustomer.phone" class="{{ $inputClass }}">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $labelClass }}">Address</label>
                                    <textarea rows="2" x-model="picker.newCustomer.address" class="{{ $inputClass }}"></textarea>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="{{ $labelClass }}">VAT / UID no.</label>
                                    <input type="text" x-model="picker.newCustomer.vat_no" class="{{ $inputClass }}">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-2">
                            <button type="button" @click="closePicker()"
                                    class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 rounded-md hover:bg-slate-100">Cancel</button>
                            <button type="button" @click="saveNewCustomer()" :disabled="picker.saving"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-black text-white text-sm font-semibold rounded-md disabled:opacity-60 disabled:cursor-not-allowed">
                                <template x-if="picker.saving">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                </template>
                                <span x-text="picker.saving ? 'Saving…' : 'Save & use'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    function invoiceForm(state, routes) {
        let key = 0;
        const emptyClient = { name: '', email: '', company: '', phone: '', address: '', vat_no: '' };
        const emptyNew    = { name: '', email: '', company: '', phone: '', address: '', vat_no: '' };
        return {
            items: (state.items || []).map(i => ({ total: 0, vat_rate: 0, ...i, _key: ++key })),
            discount: state.discount || 0,
            defaultVat: state.defaultVat || 0,
            currency: state.currency || 'CHF',
            client: { ...emptyClient, ...(state.client || {}) },
            customerId: state.customerId || null,

            picker: {
                open: false,
                mode: 'search',
                query: '',
                loading: false,
                results: [],
                searchTimer: null,
                newCustomer: { ...emptyNew },
                saving: false,
                errors: {},
            },

            addItem() { this.items.push({ description: '', total: 0, vat_rate: 0, _key: ++key }); },
            removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); },

            openPicker() {
                this.picker.open = true;
                this.picker.mode = 'search';
                this.picker.query = '';
                this.picker.results = [];
                Object.keys(emptyNew).forEach(k => this.picker.newCustomer[k] = '');
                this.picker.errors = {};
                this.fetchCustomers();
                this.$nextTick(() => {
                    const el = document.getElementById('customerPickerSearch');
                    if (el) el.focus();
                });
            },
            closePicker() { this.picker.open = false; },

            onQueryInput() {
                clearTimeout(this.picker.searchTimer);
                this.picker.searchTimer = setTimeout(() => this.fetchCustomers(), 200);
            },

            async fetchCustomers() {
                this.picker.loading = true;
                try {
                    const url = new URL(routes.search, window.location.origin);
                    if (this.picker.query) url.searchParams.set('q', this.picker.query);
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    this.picker.results = json.data || [];
                } catch (e) {
                    this.picker.results = [];
                } finally {
                    this.picker.loading = false;
                }
            },

            selectCustomer(c) {
                this.client.name    = c.name    || '';
                this.client.email   = c.email   || '';
                this.client.company = c.company || '';
                this.client.phone   = c.phone   || '';
                this.client.address = c.address || '';
                this.client.vat_no  = c.vat_no  || '';
                this.customerId = c.id || null;
                this.closePicker();
            },

            clearCustomer() {
                this.customerId = null;
                Object.keys(emptyClient).forEach(k => this.client[k] = '');
            },

            async saveNewCustomer() {
                this.picker.saving = true;
                this.picker.errors = {};
                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    const res = await fetch(routes.store, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token || '',
                        },
                        body: JSON.stringify(this.picker.newCustomer),
                    });
                    if (res.status === 422) {
                        const err = await res.json();
                        this.picker.errors = err.errors || {};
                        return;
                    }
                    if (!res.ok) throw new Error('Save failed');
                    const json = await res.json();
                    this.selectCustomer(json.data);
                } catch (e) {
                    alert('Could not save customer.');
                } finally {
                    this.picker.saving = false;
                }
            },

            num(v) { const n = parseFloat(v); return isNaN(n) ? 0 : n; },
            round(v) { return Math.round((v + Number.EPSILON) * 100) / 100; },
            // Per spec: user types Total (gross, VAT-inclusive) + VAT%. Net is derived.
            lineNet(item) {
                const total = this.num(item.total);
                const vat   = this.num(item.vat_rate);
                if (vat <= 0) return this.round(total);
                return this.round(total * 100 / (100 + vat));
            },
            // Footer: Sub Total = Σ gross, Total VAT = Σ % (spec quirk), Total = Sub Total − Discount
            subtotal()     { return this.round(this.items.reduce((s, i) => s + this.num(i.total), 0)); },
            totalVat()     { return this.items.reduce((s, i) => s + this.num(i.vat_rate), 0); },
            discountValue(){ return Math.max(0, Math.min(this.num(this.discount), this.subtotal())); },
            total()        { return this.round(this.subtotal() - this.discountValue()); },
            fmt(v)    { return this.num(v).toLocaleString('de-CH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },
            fmtVat(v) { const n = this.num(v); return (Math.round(n * 100) / 100).toString() + ' %'; },
        };
    }
</script>
@endpush
