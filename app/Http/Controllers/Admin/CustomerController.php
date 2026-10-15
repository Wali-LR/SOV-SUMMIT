<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        $customers = Customer::query()
            ->search($term)
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email', 'company', 'phone', 'address', 'vat_no']);

        return response()->json(['data' => $customers]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'address' => ['nullable', 'string', 'max:1000'],
            'vat_no' => ['nullable', 'string', 'max:60'],
        ]);

        $data['created_by'] = $request->user()->id;

        $customer = Customer::create($data);

        return response()->json([
            'data' => $customer->only(['id', 'name', 'email', 'company', 'phone', 'address', 'vat_no']),
        ], 201);
    }
}
