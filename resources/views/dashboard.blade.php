<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Odin</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            display: inline-block;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        li:last-child {
            border-bottom: none;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-links a {
            padding: 8px 16px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .nav-links a.red {
            background: #e74c3c;
        }

        .nav-links a.green {
            background: #2ecc71;
        }

        .nav-links a.purple {
            background: #9b59b6;
        }

        .nav-links a.orange {
            background: #f39c12;
        }

        .notifications-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .notifications-box h3 {
            margin-top: 0;
            color: #856404;
        }

        .notif-item {
            padding: 8px 0;
            border-bottom: 1px solid #ffe69c;
            font-size: 14px;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="top-bar">
            <h1>Bienvenue, {{ auth()->user()->name }} !</h1>
            <div class="nav-links">
                <a href="{{ route('links.favorites') }}">⭐ Favoris</a>
                <a href="{{ route('links.trashed') }}" class="orange">🗑 Corbeille</a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('activity_logs.index') }}" class="purple">📜 Historique</a>
                <a href="{{ route('users.index') }}" class="green">👥 Utilisateurs</a>
                @endif
                <form method="POST" action="/logout" style="display:inline;">
                    @csrf
                    <button type="submit" style="padding: 8px 16px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">Logout</button>
                </form>
            </div>
        </div>

        {{-- Notifications --}}
        @if(isset($notifications) && $notifications->count() > 0)
        <div class="notifications-box">
            <h3>🔔 Notifications ({{ $notifications->count() }})</h3>
            @foreach($notifications as $notification)
            <div class="notif-item">
                {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                <small style="color: #999;">— {{ $notification->created_at->diffForHumans() }}</small>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Message de succès --}}
        @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
        @endif

        {{-- Erreurs de validation --}}
        @if($errors->any())
        <div class="error-box">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Formulaires: seulement pour Admin et Editor --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
        <div style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">

            <div class="card">
                <h2>Ajouter Catégorie</h2>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Nom de la catégorie" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">
                    <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; cursor: pointer;">Ajouter</button>
                </form>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="card">
                <h2>Ajouter Tag</h2>
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Nom du tag" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">
                    <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; cursor: pointer;">Ajouter</button>
                </form>
            </div>
            @endif

            <div class="card">
                <h2>Ajouter Lien</h2>
                <form action="{{ route('links.store') }}" method="POST">
                    @csrf
                    <input type="text" name="title" placeholder="Titre du lien" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">
                    <input type="url" name="url" placeholder="URL (https://...)" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">

                    <select name="category_id" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                        <option value="">Choisir catégorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <div style="margin-bottom: 10px;">
                        <label>Tags :</label><br>
                        @foreach($tags as $tag)
                        <label style="display: inline-block; margin-right: 10px;">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ $tag->name }}
                        </label>
                        @endforeach
                    </div>

                    <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 16px; cursor: pointer;">Ajouter</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Recherche --}}
        <div class="card" style="margin-bottom: 20px;">
            <h2>Recherche & Filtre</h2>
            <form action="{{ route('dashboard') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" style="flex: 1; padding: 8px;">

                <select name="category_id" style="padding: 8px;">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                <select name="tag_id" style="padding: 8px;">
                    <option value="">Tous tags</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; cursor: pointer;">Rechercher</button>
                <a href="{{ route('dashboard') }}" style="padding: 8px 16px; text-decoration: none; color: #666; border: 1px solid #ccc; background: #eee;">Effacer</a>
            </form>
        </div>

        <div class="grid">
            {{-- Catégories --}}
            <div class="card">
                <h2>Catégories</h2>
                @if($categories->count() > 0)
                <ul>
                    @foreach($categories as $category)
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        {{ $category->name }}
                        @if(auth()->user()->isAdmin() || (auth()->user()->isEditor() && auth()->user()->id === $category->user_id))
                        <div>
                            <a href="{{ route('categories.edit', $category->id) }}" style="margin-right: 10px; color: #f39c12;">Modifier</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;">Supprimer</button>
                            </form>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <p>Aucune catégorie.</p>
                @endif
            </div>

            {{-- Liens --}}
            <div class="card">
                <h2>Liens</h2>
                @if($links->count() > 0)
                <ul>
                    @foreach($links as $link)
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <a href="{{ $link->url }}" target="_blank">{{ $link->title ?? $link->url }}</a>
                            @if($link->category)
                            <small style="color: #999;">[{{ $link->category->name }}]</small>
                            @endif
                        </div>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            {{-- Favori --}}
                            <form action="{{ route('links.toggle_favorite', $link->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 16px;">
                                    @if(in_array($link->id, $favoriteIds)) ⭐ @else ☆ @endif
                                </button>
                            </form>

                            {{-- Edit/Delete: seulement si autorisé --}}
                            @if(auth()->user()->isAdmin() || auth()->user()->id === $link->user_id)
                            <a href="{{ route('links.edit', $link->id) }}" style="color: #f39c12;">Modifier</a>
                            <form action="{{ route('links.destroy', $link->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;">Supprimer</button>
                            </form>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p>Aucun lien.</p>
                @endif
            </div>

            {{-- Tags --}}
            <div class="card">
                <h2>Tags</h2>
                @if($tags->count() > 0)
                <ul>
                    @foreach($tags as $tag)
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        #{{ $tag->name }}
                        @if(auth()->user()->isAdmin())
                        <div>
                            <a href="{{ route('tags.edit', $tag->id) }}" style="margin-right: 10px; color: #f39c12;">Modifier</a>
                            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;">Supprimer</button>
                            </form>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <p>Aucun tag.</p>
                @endif
            </div>
        </div>
    </div>

</body>

</html>