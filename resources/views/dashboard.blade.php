<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Odin Brief</title>
    <title>Dashboard - Odin Brief</title>
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

        .logout-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            float: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <form method="POST" action="/logout" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <h1>Welcome, {{ auth()->user()->name }}!</h1>

        <div style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">

            <div class="card">
                <h2>Add Category</h2>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Category Name" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                    <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; cursor: pointer;">Add Category</button>
                </form>
            </div>

            <div class="card">
                <h2>Add Tag</h2>
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Tag Name" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                    <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; cursor: pointer;">Add Tag</button>
                </form>
            </div>

            <div class="card">
                <h2>Add Link</h2>
                <form action="{{ route('links.store') }}" method="POST">
                    @csrf
                    <input type="text" name="title" placeholder="Link Title" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">
                    <input type="url" name="url" placeholder="URL (https://...)" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">

                    <select name="category_id" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <div style="margin-bottom: 10px;">
                        <label>Select Tags:</label><br>
                        @foreach($tags as $tag)
                        <label style="display: inline-block; margin-right: 10px;">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ $tag->name }}
                        </label>
                        @endforeach
                    </div>

                    <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 16px; cursor: pointer;">Add Link</button>
                </form>
            </div>
        </div>

        <div class="card" style="margin-bottom: 20px;">
            <h2>Search & Filter</h2>
            <form action="{{ route('dashboard') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="Search links..." value="{{ request('search') }}" style="flex: 1; padding: 8px;">

                <select name="category_id" style="padding: 8px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                <select name="tag_id" style="padding: 8px;">
                    <option value="">All Tags</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" style="background: #3498db; color: white; border: none; padding: 8px 16px; cursor: pointer;">Search</button>
                <a href="{{ route('dashboard') }}" style="padding: 8px 16px; text-decoration: none; color: #666; border: 1px solid #ccc; background: #eee;">Clear</a>
            </form>
        </div>

        <div class="grid">
            <div class="card">
                <h2>Categories</h2>
                @if($categories->count() > 0)
                <ul>
                    @foreach($categories as $category)
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        {{ $category->name }}
                        <div>
                            <a href="{{ route('categories.edit', $category->id) }}" style="margin-right: 10px; color: #f39c12;">Edit</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;">Delete</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p>No categories found.</p>
                @endif
            </div>

            <div class="card">
                <h2>Links</h2>
                @if($links->count() > 0)
                <ul>
                    @foreach($links as $link)
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        <a href="{{ $link->url }}" target="_blank">{{ $link->title ?? $link->url }}</a>
                        <div>
                            <a href="{{ route('links.edit', $link->id) }}" style="margin-right: 10px; color: #f39c12;">Edit</a>
                            <form action="{{ route('links.destroy', $link->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;">Delete</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p>No links found.</p>
                @endif
            </div>

            <div class="card">
                <h2>Tags</h2>
                @if($tags->count() > 0)
                <ul>
                    @foreach($tags as $tag)
                    <li style="display: flex; justify-content: space-between; align-items: center;">
                        #{{ $tag->name }}
                        <div>
                            <a href="{{ route('tags.edit', $tag->id) }}" style="margin-right: 10px; color: #f39c12;">Edit</a>
                            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer;">Delete</button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p>No tags found.</p>
                @endif
            </div>
        </div>
    </div>

</body>

</html>