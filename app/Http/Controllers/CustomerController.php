<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view customers', only: ['index', 'show']),
            new Middleware('permission:create customers', only: ['create', 'store']),
            new Middleware('permission:edit customers', only: ['edit', 'update']),
            new Middleware('permission:delete customers', only: ['destroy']),
        ];
    }

    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('customers.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'referred_by' => 'nullable|exists:customers,id',
        ]);

        $customer = DB::transaction(function () use ($request) {
            $account = Account::create([
                'name' => $request->name . ' (عميل)',
                'code' => 'CUST-' . time() . rand(10,99),
                'type' => 'asset',
            ]);

            return Customer::create([
                'account_id' => $account->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'phone_2' => $request->phone_2,
                'city' => $request->city,
                'address' => $request->address,
                'referred_by' => $request->referred_by,
            ]);
        });

        if ($request->has('quick_ajax')) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function edit(Customer $customer)
    {
        $customers = Customer::where('id', '!=', $customer->id)->get();
        return view('customers.edit', compact('customer', 'customers'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'referred_by' => 'nullable|exists:customers,id',
        ]);

        DB::transaction(function () use ($request, $customer) {
            $customer->update($request->only('name', 'phone', 'phone_2', 'city', 'address', 'referred_by'));
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
