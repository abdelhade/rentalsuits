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
        return view('rentals.create', compact('date', 'customers', 'items'));
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
        ]);

        $invoice = new \App\Models\Invoice();
        $invoice->invoice_number = 'R-' . mt_rand(100000, 999999); // Generate a random invoice number
        $invoice->type = 'rent';
        $invoice->entity_id = $request->customer_id;
        $invoice->date = $request->date;
        $invoice->total_amount = $request->total_amount;
        $invoice->paid_amount = $request->paid_amount;
        $invoice->save();

        foreach ($request->items as $itemData) {
            $item = new \App\Models\InvoiceItem();
            $item->invoice_id = $invoice->id;
            $item->item_id = $itemData['item_id'];
            $item->qty = $itemData['qty'];
            $item->price = $itemData['price'];
            $item->total = $itemData['total'] ?? ($itemData['qty'] * $itemData['price']);
            $item->save();
            
            // Optionally update item status if needed
            // $invItem = \App\Models\Item::find($itemData['item_id']);
            // if ($invItem) {
            //     $invItem->status = 'rented';
            //     $invItem->save();
            // }
        }

        return redirect()->route('rentals.create')->with('success', 'تم حفظ الفاتورة بنجاح');
    }
}
