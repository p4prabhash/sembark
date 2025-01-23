<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Short URLs</title>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 50px auto;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .actions button, .actions select {
            font-size: 16px;
            padding: 10px 15px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .actions button:hover, .actions select:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination .disabled {
            background-color: #cccccc;
            color: #666666;
        }
    </style>
</head>
<body>
@include('dashboard')

<div class="container">
@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

<h1>Generated Short URLs</h1>

<!-- Actions Section -->
<div class="actions">
    @if (auth()->user()->role === 'member')
        <button onclick="window.location.href='{{ route('urls.create') }}'">Generate</button>
    @endif
    <div>
        <!-- Filter Dropdown -->
        <select id="filter" onchange="submitFilter()">
            <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
            <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
            <option value="last_week" {{ request('filter') == 'last_week' ? 'selected' : '' }}>Last Week</option>
            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
        </select>
        <button onclick="window.location.href='{{ route('urls.download', ['filter' => request('filter')]) }}'">Download CSV</button>

    </div>
</div>

<!-- URLs Table -->
<table>
    <thead>
        <tr>
            <th>Short URL</th>
            <th>Long URL</th>
            <th>Hits</th>
            <th>Created By</th>
            <th>Created On</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($urls as $url)
            <tr>
                <td><a href="{{ $url->short_url }}" target="_blank">{{ $url->short_url }}</a></td>
                <td>{{ \Illuminate\Support\Str::limit($url->long_url, 30) }}</td>
                <td>{{ $url->hits }}</td>
                <td>{{ $url->user->name ?? 'NA' }}</td>
                <td>{{ $url->created_at->format('d M \'y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No URLs found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Links -->
<div class="pagination">
    {{ $urls->links('pagination::bootstrap-4') }}
</div>
</div>

<script>
    function submitFilter() {
        var filterValue = document.getElementById('filter').value;
       
        window.location.href = '?filter=' + filterValue;
    }
</script>
</body>
</html>