<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - Odin</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .empty {
            color: #999;
            text-align: center;
            padding: 40px;
        }

        .link-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .link-item:last-child {
            border-bottom: none;
        }

        .btn {
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 14px;
            background: #e74c3c;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Retour au Dashboard</a>
        <h1>⭐ Mes Favoris</h1>

        @if($links->count() > 0)
        <div class="card">
            @foreach($links as $link)
            <div class="link-item">
                <div>
                    <a href="{{ $link->url }}" target="_blank" style="color: #2c3e50; font-weight: bold;">{{ $link->title }}</a>
                    <br><small style="color: #999;">{{ $link->url }}</small>
                </div>
                <form action="{{ route('links.toggle_favorite', $link->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">Retirer ⭐</button>
                </form>
            </div>
            @endforeach
        </div>

        {{ $links->links() }}
        @else
        <div class="card empty">
            <p>Aucun favori pour le moment.</p>
        </div>
        @endif
    </div>
</body>

</html>