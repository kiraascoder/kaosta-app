<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer_index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        Customer::create($validated);

        return redirect()->route('customer_index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customer_index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customer_index')->with('success', 'Customer berhasil dihapus.');
    }
}
