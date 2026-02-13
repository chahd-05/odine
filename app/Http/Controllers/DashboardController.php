<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Link::query();

        // Permission: Admin voit tout, Editor/Viewer voient seulement leurs liens + partagés
        if (!$user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('users', function ($sub) use ($user) {
                        $sub->where('user_id', $user->id);
                    });
            });
        }

        // Search by title or URL
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by Tag
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->input('tag_id'));
            });
        }

        $links = $query->with(['category', 'tags'])->get();
        $categories = Category::all();
        $tags = Tag::all();
        $notifications = $user->unreadNotifications;

        // Charger les IDs des favoris pour éviter N+1
        $favoriteIds = $user->favorites()->pluck('links.id')->toArray();

        return view('dashboard', compact('categories', 'links', 'tags', 'notifications', 'favoriteIds'));
    }
}
