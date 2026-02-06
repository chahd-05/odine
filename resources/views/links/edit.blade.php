<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Link</title>
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

        input,
        select {
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

        .tags {
            margin-bottom: 20px;
        }

        .tags label {
            display: inline-block;
            margin-right: 15px;
        }
    </style>
</head>

<body>

    <div class="card">
        <h2>Edit Link</h2>
        <form action="{{ route('links.update', $link->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Title:</label>
            <input type="text" name="title" value="{{ $link->title }}" required>

            <label>URL:</label>
            <input type="url" name="url" value="{{ $link->url }}" required>

            <label>Category:</label>
            <select name="category_id" required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $link->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>

            <div class="tags">
                <label>Tags:</label><br>
                @foreach($tags as $tag)
                <label>
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        {{ $link->tags->contains($tag->id) ? 'checked' : '' }}>
                    {{ $tag->name }}
                </label>
                @endforeach
            </div>

            <button type="submit">Update Link</button>
            <a href="{{ route('dashboard') }}">Cancel</a>
        </form>
    </div>

</body>

</html>