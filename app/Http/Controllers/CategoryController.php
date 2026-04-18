<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories, or tree structure. We will just list them by pulling ones without parent
        // and let view handle tree rendering recursively.
        $categories = Category::whereNull('parent_id')->with('children')->get();
        // Also get flat for the table/dropdown if needed
        $allCategories = Category::with('parent')->get();
        return view('categories.index', compact('categories', 'allCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $level = 0;
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            $level = $parent->level + 1;
        }

        Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'level' => $level
        ]);

        return redirect()->route('categories.index')->with('success', 'تم إضافة المجموعة بنجاح');
    }

    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get(); // exclude self
        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'لا يمكن أن تكون المجموعة أباً لنفسها']);
        }

        $level = 0;
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            $level = $parent->level + 1;
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'level' => $level
        ]);

        return redirect()->route('categories.index')->with('success', 'تم تعديل المجموعة بنجاح');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المجموعة لوجود مجموعات فرعية تابعة لها');
        }
        if ($category->items()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المجموعة لاحتوائها على أصناف');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'تم الحذف بنجاح');
    }
}
