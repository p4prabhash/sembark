<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 168px;
            background-color: #007bff;
            color: white;
            height: 100vh;
            padding: 5px;
            /* position: fixed; */
        }

        .sidebar h2 {
            text-align: center;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #0056b3;
        }

        .content {
            margin-left: 250px; /* Same as sidebar width */
            padding: 20px;
            width: calc(100% - 250px);
        }

        h1 {
            text-align: center;
        }

        .welcome {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="{{ route('dashboard') }}">Home</a>
        @if (auth()->user()->role === 'superadmin')
            <a href="{{ route('invited.customers') }}">Clients</a>
            <a href="{{ route('urls.index') }}">Short Urls</a>
        @endif

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('members') }}">Team Member</a>
            <a href="{{ route('urls.index') }}">Short Urls</a>

        @endif
        @if (auth()->user()->role === 'member')
            <a href="{{ route('urls.index') }}">Short Urls</a>

        @endif
       

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" style="background: none; border: none; color: blue; cursor: pointer;">
            Logout
        </button>
    </form>
    </div>
    
</body>
</html>
