<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array'
        ]);

        $link = Link::create([
            'title' => $request->title,
            'url' => $request->url,
            'category_id' => $request->category_id,
            'user_id' => Auth::id()
        ]);

        if ($request->has('tags')) {
            $link->tags()->attach($request->tags);
        }

        return redirect()->route('dashboard');
    }

    public function edit($id)
    {
        $link = Link::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        return view('links.edit', compact('link', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array'
        ]);

        $link = Link::findOrFail($id);
        $link->update([
            'title' => $request->title,
            'url' => $request->url,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $link->tags()->sync($request->tags);
        } else {
            $link->tags()->detach();
        }

        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $link = Link::findOrFail($id);
        $link->delete();

        return redirect()->route('dashboard');
    }
}
