<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $account = Account::create([
                'name' => $request->name . ' (مورد)',
                'code' => 'SUPP-' . time() . rand(10,99),
                'type' => 'liability',
            ]);

            Supplier::create([
                'account_id' => $account->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'city' => $request->city,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('suppliers.index')->with('success', 'تم إضافة المورد بنجاح');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $supplier) {
            $supplier->update($request->only('name', 'phone', 'city', 'address'));
            $supplier->account()->update(['name' => $request->name . ' (مورد)']);
        });

        return redirect()->route('suppliers.index')->with('success', 'تم تحديث المورد بنجاح');
    }

    public function destroy(Supplier $supplier)
    {
        DB::transaction(function () use ($supplier) {
            $accountId = $supplier->account_id;
            $supplier->delete();
            Account::where('id', $accountId)->delete();
        });

        return redirect()->route('suppliers.index')->with('success', 'تم حذف المورد بنجاح');
    }
}
