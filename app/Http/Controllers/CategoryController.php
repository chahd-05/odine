<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $request->validate(['name' => 'required|string|max:255']);

        Category::create([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('dashboard');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $category = Category::findOrFail($id);
        $this->authorize('update', $category);
        $category->update(['name' => $request->name]);

        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()->route('dashboard');
    }
}
