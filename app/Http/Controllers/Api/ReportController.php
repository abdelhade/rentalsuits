<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;

class ReportController extends Controller
{
    public function ledger(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));

        // Load invoices for the given day along with the customer
        $invoices = Invoice::with(['customer', 'invoiceItems.item'])
            ->whereDate('date', $date)
            ->where('type', 'rent')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }
}
