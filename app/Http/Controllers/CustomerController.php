<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
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
                'name' => $request->name . ' (عميل)',
                'code' => 'CUST-' . time() . rand(10,99),
                'type' => 'asset',
            ]);

            Customer::create([
                'account_id' => $account->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'city' => $request->city,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('customers.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $customer) {
            $customer->update($request->only('name', 'phone', 'city', 'address'));
            $customer->account()->update(['name' => $request->name . ' (عميل)']);
        });

        return redirect()->route('customers.index')->with('success', 'تم تحديث العميل بنجاح');
    }

    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {
            $accountId = $customer->account_id;
            $customer->delete();
            Account::where('id', $accountId)->delete();
        });

        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }
}
