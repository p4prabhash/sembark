<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation List</title>
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
            padding: 15px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
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
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        h1 {
            text-align: center;
        }

        .invitation-list {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status {
            font-weight: bold;
        }

        .status.pending {
            color: orange;
        }

        .status.accepted {
            color: green;
        }

        .status.declined {
            color: red;
        }
    </style>
</head>
<body>
@include('dashboard')

    <div class="content">
        <h1>Client Admin List</h1>
          <!-- Create Invitation Button -->
    <div style="margin-bottom: 20px; text-align: right;">
        <a href="{{ route('invitation.create') }}" style="
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background 0.3s;
        ">Create Invitation</a>
    </div>
        <!-- Filter Form -->
        <form method="GET" action="{{ route('invited.customers') }}" style="margin-bottom: 20px;">
            <label for="status">Filter by Status:</label>
            <select id="status" name="status" onchange="this.form.submit()">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Not Signed Up</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Signed Up</option>
            </select>
        </form>

        <!-- Invitation List -->
        <div class="invitation-list">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invitations as $invitation)
                        <tr>
                            <td>{{ $invitation->id }}</td>
                            <td>{{ $invitation->email }}</td>
                            <td>
                                <span class="status {{ $invitation->is_signed_up ? 'accepted' : 'pending' }}">
                                    {{ $invitation->is_signed_up ? 'Signed Up' : 'Not Signed Up' }}
                                </span>
                            </td>
                            <td>{{ $invitation->created_at->format('Y-m-d h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">No invitations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div style="margin-top: 20px;">
                {{ $invitations->links() }}
            </div>
        </div>
    </div>
</body>
</html>
