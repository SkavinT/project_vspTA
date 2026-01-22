<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->paginate(10);
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'email.email' => 'Format email tidak valid.',
        ];

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email', // tanpa unique di sini
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ], $messages);

        // Cek manual apakah email supplier sudah ada
        if (!empty($validated['email']) && Supplier::where('email', $validated['email'])->exists()) {
            return back()
                ->withErrors(['email' => 'Email supplier ini sudah terdaftar, gunakan email lain.'])
                ->withInput();
        }

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $messages = [
            'email.email' => 'Format email tidak valid.',
        ];

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ], $messages);

        if (!empty($validated['email'])) {
            $exists = Supplier::where('email', $validated['email'])
                ->where('id', '!=', $supplier->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['email' => 'Email supplier ini sudah terdaftar, gunakan email lain.'])
                    ->withInput();
            }
        }

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
