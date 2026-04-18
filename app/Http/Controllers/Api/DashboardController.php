<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $invoices = Invoice::with('customer')
            ->whereBetween('date', [
                date('Y-m-d', strtotime($start)),
                date('Y-m-d', strtotime($end))
            ])->get();

        $events = [];
        foreach ($invoices as $inv) {
            $events[] = [
                'id' => $inv->id,
                'title' => ($inv->type === 'rent' ? 'تأجير: ' : 'عملية: ') . ($inv->customer ? $inv->customer->name : 'غير محدد'),
                'start' => $inv->date,
                'className' => 'fc-event-' . $inv->type
            ];
        }

        return response()->json($events);
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        $customerId = $request->customer_id;
        
        // Auto create customer for simplicity if not exists (In real life, handle via form)
        // Since we have select2, let's assume it gets ID or we can simulate
        // For the sake of the demo, we assume customer_id might be empty.
        if (!$customerId) {
            $customer = Customer::create([
                'account_id' => 1, // Fallback, normally we create account via Service
                'name' => 'عميل نقدي ' . time(),
            ]);
            $customerId = $customer->id;
        }

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(6)),
            'type' => $request->type,
            'entity_id' => $customerId,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount ?? 0,
            'date' => $request->date,
            'notes' => $request->details ?? '',
        ]);

        return response()->json(['success' => true, 'data' => $invoice]);
    }
}
