<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Http\Requests\ShareLinkRequest;
use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Events\LinkDeleted;
use App\Events\LinkRestored;
use App\Events\LinkForceDeleted;
use App\Events\LinkShared;

class LinkController extends Controller
{
    public function store(StoreLinkRequest $request)
    {
        $link = Link::create([
            'title'       => $request->title,
            'url'         => $request->url,
            'category_id' => $request->category_id,
            'user_id'     => Auth::id(),
        ]);

        if ($request->has('tags')) {
            $link->tags()->attach($request->tags);
        }

        event(new LinkCreated($link, Auth::user()));

        return redirect()->route('dashboard')->with('success', 'Lien créé avec succès.');
    }

    public function edit($id)
    {
        $link = Link::findOrFail($id);
        $this->authorize('update', $link);
        $categories = Category::all();
        $tags = Tag::all();

        return view('links.edit', compact('link', 'categories', 'tags'));
    }

    public function update(UpdateLinkRequest $request, $id)
    {
        $link = Link::findOrFail($id);
        $this->authorize('update', $link);

        $link->update([
            'title'       => $request->title,
            'url'         => $request->url,
            'category_id' => $request->category_id,
        ]);

        if ($request->has('tags')) {
            $link->tags()->sync($request->tags);
        } else {
            $link->tags()->detach();
        }

        event(new LinkUpdated($link, Auth::user()));

        return redirect()->route('dashboard')->with('success', 'Lien modifié avec succès.');
    }

    public function destroy($id)
    {
        $link = Link::findOrFail($id);
        $this->authorize('delete', $link);
        $link->delete();

        event(new LinkDeleted($link, Auth::user()));

        return redirect()->route('dashboard')->with('success', 'Lien supprimé avec succès.');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $links = Link::with('user')->latest()->paginate(20);
        } else {
            $links = Link::where('user_id', $user->id)
                ->orWhereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with('user')
                ->latest()
                ->paginate(20);
        }

        return view('links.index', compact('links'));
    }

    public function share(ShareLinkRequest $request, Link $link)
    {
        $this->authorize('update', $link);

        $userToShare = User::where('email', $request->email)->first();

        if ($userToShare->id === $link->user_id) {
            return back()->with('error', 'Vous ne pouvez pas partager le lien avec vous-même.');
        }

        $existingPivot = $link->users()->where('user_id', $userToShare->id)->first();

        $link->users()->syncWithoutDetaching([
            $userToShare->id => ['access_level' => $request->access_level],
        ]);

        event(new LinkShared($link, Auth::user(), $userToShare, $request->access_level));

        if (!$existingPivot) {
            $userToShare->notify(new \App\Notifications\LinkSharedNotification($link, Auth::user()));
        } elseif ($existingPivot->pivot->access_level !== $request->access_level) {
            $userToShare->notify(new \App\Notifications\AccessChangedNotification($link, $request->access_level));
        }

        return back()->with('success', 'Lien partagé avec succès.');
    }

    public function trashed()
    {
        $this->authorize('viewAny', Link::class);

        $user = Auth::user();
        if ($user->isAdmin()) {
            $links = Link::onlyTrashed()->with('user')->paginate(20);
        } else {
            $links = Link::onlyTrashed()->where('user_id', $user->id)->paginate(20);
        }

        return view('links.trashed', compact('links'));
    }

    public function restore($id)
    {
        $link = Link::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $link);
        $link->restore();

        event(new LinkRestored($link, Auth::user()));

        return back()->with('success', 'Lien restauré avec succès.');
    }

    public function forceDelete($id)
    {
        $link = Link::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $link);

        $title = $link->title;
        $linkId = $link->id;
        $link->forceDelete();

        event(new LinkForceDeleted($title, $linkId, Auth::user()));

        return back()->with('success', 'Lien supprimé définitivement.');
    }

    public function toggleFavorite($id)
    {
        $link = Link::findOrFail($id);
        Auth::user()->favorites()->toggle($link->id);

        return back();
    }

    public function favorites()
    {
        $links = Auth::user()->favorites()->latest()->paginate(20);

        return view('links.favorites', compact('links'));
    }
}
