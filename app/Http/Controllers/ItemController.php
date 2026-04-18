<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view items', only: ['index', 'show']),
            new Middleware('permission:create items', only: ['create', 'store']),
            new Middleware('permission:edit items', only: ['edit', 'update']),
            new Middleware('permission:delete items', only: ['destroy']),
        ];
    }

    public function index()
    {
        $items = Item::with('category')->latest()->paginate(10);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:items,barcode',
            'purchase_price' => 'required|numeric|min:0',
            'rental_price' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,sold',
            'description' => 'nullable|string',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'تم إضافة الصنف بنجاح');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:50|unique:items,barcode,'.$item->id,
            'purchase_price' => 'required|numeric|min:0',
            'rental_price' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,sold',
            'description' => 'nullable|string',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'تم تحديث الصنف بنجاح');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'تم الحذف بنجاح');
    }
}
