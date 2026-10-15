<x-admin-layout title="New Invoice" subtitle="Create an invoice and email it to your client">
    <x-slot name="actions">
        <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Back to invoices
        </a>
    </x-slot>

    <form action="{{ route('admin.invoices.store') }}" method="POST">
        @include('admin.invoices._form')
    </form>
</x-admin-layout>
