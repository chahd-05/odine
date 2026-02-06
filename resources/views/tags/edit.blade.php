<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tag</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f4f6f9;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 50px auto;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        a {
            text-decoration: none;
            color: #555;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Edit Tag</h2>
        <form action="{{ route('tags.update', $tag->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Tag Name:</label>
            <input type="text" name="name" value="{{ $tag->name }}" required>

            <button type="submit">Update Tag</button>
            <a href="{{ route('dashboard') }}">Cancel</a>
        </form>
    </div>

</body>

</html>