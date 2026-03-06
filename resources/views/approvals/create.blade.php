<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Approval Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #edf2f7;
            padding: 40px;
        }

        h2 {
            color: #1f2937;
            text-align: center;
            margin-bottom: 20px;
        }

        .back-button {
            display: block;
            margin: 0 auto 20px auto;
            text-align: center;
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

        form {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        button {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background: #2563eb;
        }
    </style>
</head>

<body>

    <div class="back-button">
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </div>

    <h2>Create Approval Request</h2>
    <form action="{{ route('approvals.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="Request Title" required>
        <textarea name="description" placeholder="Request Description" rows="5" required></textarea>
        <button type="submit">Submit Request</button>
    </form>

</body>

</html>