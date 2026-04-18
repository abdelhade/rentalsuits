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
}
