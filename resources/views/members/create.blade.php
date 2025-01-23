<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite New Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        /* Top navigation bar */
        .navbar {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin-left: 20px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Main content area */
        .content {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: 1px solid #ddd;
        }

        .content h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form design */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            width: calc(100% - 20px);
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    @include('dashboard')
    <!-- Main Content -->
    <div class="content">
        <h1>Invite New Client</h1>
        <form method="POST" action="{{ route('send.invitemember') }}">
            @csrf
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder=" Name..." required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="ex. sample@example.com" required>

            <button type="submit">Send Invitation</button>
        </form>
    </div>
</body>
</html>
