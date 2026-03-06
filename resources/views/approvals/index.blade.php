<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Approval Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf2f7;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 20px;
        }

        .back-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-button a {
            display: inline-block;
            background: #6b7280;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }

        .back-button a:hover {
            background: #4b5563;
        }

        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #3b82f6;
            color: #fff;
        }

        tr:hover {
            background: #f1f5f9;
        }

        .status-pending {
            color: #f59e0b;
            font-weight: bold;
        }

        .status-approved {
            color: #10b981;
            font-weight: bold;
        }

        .status-rejected {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="back-button">
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </div>

    <h2>All Approval Requests</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>User</th>
            <th>Status</th>
        </tr>
        @foreach($requests as $r)
            <tr>
                <td>{{ $r->title }}</td>
                <td>{{ $r->user->name }}</td>
                <td class="status-{{ strtolower($r->status) }}">{{ ucfirst($r->status) }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>