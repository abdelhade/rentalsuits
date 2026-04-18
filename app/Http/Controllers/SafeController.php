<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SafeController extends Controller
{
    public function index()
    {
        $safes = Safe::latest()->paginate(10);
        return view('safes.index', compact('safes'));
    }

    public function create()
    {
        return view('safes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $account = Account::create([
                'name' => $request->name,
                'code' => 'SAFE-' . time() . rand(10,99),
                'type' => 'asset',
            ]);

            Safe::create([
                'account_id' => $account->id,
                'name' => $request->name,
            ]);
        });

        return redirect()->route('safes.index')->with('success', 'تم إضافة الصندوق بنجاح');
    }

    public function edit(Safe $safe)
    {
        return view('safes.edit', compact('safe'));
    }

    public function update(Request $request, Safe $safe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $safe) {
            $safe->update($request->only('name'));
            $safe->account()->update(['name' => $request->name]);
        });

        return redirect()->route('safes.index')->with('success', 'تم تحديث الصندوق بنجاح');
    }

    public function destroy(Safe $safe)
    {
        DB::transaction(function () use ($safe) {
            $accountId = $safe->account_id;
            $safe->delete();
            Account::where('id', $accountId)->delete();
        });

        return redirect()->route('safes.index')->with('success', 'تم حذف الصندوق بنجاح');
    }
}
