<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Actions - Odin</title>
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

        .empty {
            color: #999;
            text-align: center;
            padding: 40px;
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
        }

        .badge-creation {
            background: #2ecc71;
        }

        .badge-modification {
            background: #f39c12;
        }

        .badge-suppression {
            background: #e74c3c;
        }

        .badge-restauration {
            background: #3498db;
        }

        .badge-partage {
            background: #9b59b6;
        }

        .badge-default {
            background: #95a5a6;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Retour au Dashboard</a>
        <h1>📜 Historique des Actions</h1>

        @if($logs->count() > 0)
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'Inconnu' }}</td>
                        <td>
                            @php
                            $badgeClass = match(true) {
                            str_contains($log->action, 'création') => 'badge-creation',
                            str_contains($log->action, 'modification') => 'badge-modification',
                            str_contains($log->action, 'suppression') => 'badge-suppression',
                            str_contains($log->action, 'restauration') => 'badge-restauration',
                            str_contains($log->action, 'partage') => 'badge-partage',
                            default => 'badge-default',
                            };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                        </td>
                        <td>{{ $log->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
        @else
        <div class="card empty">
            <p>Aucune action enregistrée.</p>
        </div>
        @endif
    </div>
</body>

</html>