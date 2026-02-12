<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - Odin</title>
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
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #34495e;
            color: white;
        }

        td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            color: white;
            margin-right: 4px;
        }

        .badge-admin {
            background: #e74c3c;
        }

        .badge-editor {
            background: #f39c12;
        }

        .badge-viewer {
            background: #3498db;
        }

        .btn {
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 13px;
        }

        .btn-red {
            background: #e74c3c;
        }

        .btn-green {
            background: #2ecc71;
        }

        select {
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        @if(session('success')) @endif
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Retour au Dashboard</a>
        <h1>👥 Gestion des Utilisateurs</h1>

        @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                            @php
                            $badgeClass = match($role->slug) {
                            'admin' => 'badge-admin',
                            'editor' => 'badge-editor',
                            default => 'badge-viewer',
                            };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.update', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <select name="roles[]">
                                    <option value="">Changer rôle...</option>
                                    @php $allRoles = \App\Models\Role::all(); @endphp
                                    @foreach($allRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-green">Sauvegarder</button>
                            </form>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-red">Supprimer</button>
                            </form>
                            @else
                            <span style="color: #999;">Vous</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</body>

</html>