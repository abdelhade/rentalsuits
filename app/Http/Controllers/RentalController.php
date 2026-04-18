<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;

class RentalController extends Controller
{
    public function create(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $customers = Customer::all();
        $items = Item::where('status', 'available')->get();
        $safes = \App\Models\Safe::all();
        return view('rentals.create', compact('date', 'customers', 'items', 'safes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
            'safe_id' => 'required|exists:safes,id',
            'invoice_notes' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // 1. إنشاء الفاتورة
            $invoice = new \App\Models\Invoice();
            $invoice->invoice_number = 'R-' . mt_rand(100000, 999999);
            $invoice->type = 'rent';
            $invoice->entity_id = $request->customer_id;
            $invoice->date = $request->date;
            $invoice->total_amount = $request->total_amount;
            $invoice->paid_amount = $request->paid_amount;
            $invoice->notes = $request->invoice_notes ?? null;
            if (!$invoice->save()) {
                throw new \Exception('فشل في حفظ الفاتورة');
            }

            // 2. إنشاء بنود الفاتورة
            foreach ($request->items as $index => $itemData) {
                $item = new \App\Models\InvoiceItem();
                $item->invoice_id = $invoice->id;
                $item->item_id = $itemData['item_id'];
                $item->qty = $itemData['qty'];
                $item->price = $itemData['price'];
                $item->total = $itemData['total'] ?? ($itemData['qty'] * $itemData['price']);
                $item->notes = $itemData['notes'] ?? null;
                if (!$item->save()) {
                    throw new \Exception('فشل في حفظ الصنف رقم ' . ($index + 1));
                }
            }

            // 3. إنشاء سند القبض إذا كان هناك مبلغ مدفوع
            if (floatval($request->paid_amount) > 0) {
                $voucher = new \App\Models\Voucher();
                $voucher->voucher_number = 'V-' . time() . rand(10, 99);
                $voucher->type = 'receipt';
                $voucher->safe_id = $request->safe_id;
                $voucher->account_id = $invoice->entity_id;
                $voucher->amount = $request->paid_amount;
                $voucher->date = $request->date;
                $voucher->description = 'سند قبض من الفاتورة ' . $invoice->invoice_number;
                if (!$voucher->save()) {
                    throw new \Exception('فشل في إنشاء سند القبض');
                }
            }

            \DB::commit();
            return redirect()->route('rentals.create')->with('success', 'تم حفظ الفاتورة بنجاح');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }
}
