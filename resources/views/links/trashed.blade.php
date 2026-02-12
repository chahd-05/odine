<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liens supprimés - Odin</title>
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

        .btn {
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-green {
            background: #2ecc71;
        }

        .btn-red {
            background: #e74c3c;
        }

        .btn-blue {
            background: #3498db;
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

        .actions {
            display: flex;
            gap: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Retour au Dashboard</a>
        <h1>🗑 Liens supprimés</h1>

        @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
        @endif

        @if($links->count() > 0)
        <div class="card">
            @foreach($links as $link)
            <div class="link-item">
                <div>
                    <strong>{{ $link->title }}</strong>
                    <br><small style="color: #999;">{{ $link->url }}</small>
                    <br><small style="color: #aaa;">Supprimé le {{ $link->deleted_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="actions">
                    <form action="{{ route('links.restore', $link->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-green">Restaurer</button>
                    </form>

                    @if(auth()->user()->isAdmin())
                    <form action="{{ route('links.force_delete', $link->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-red">Supprimer définitivement</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{ $links->links() }}
        @else
        <div class="card empty">
            <p>Aucun lien supprimé.</p>
        </div>
        @endif
    </div>
</body>

</html>