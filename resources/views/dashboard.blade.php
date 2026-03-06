<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Process Approval Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #edf2f7;
            margin: 0;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 30px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Cards */
        .card {
            background: #fff;
            width: 360px;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #111827;
        }

        .card p {
            margin: 5px 0;
            color: #4b5563;
        }

        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-pending {
            background: #f59e0b;
        }

        .badge-approved {
            background: #10b981;
        }

        .badge-rejected {
            background: #ef4444;
        }

        /* Form Elements */
        form {
            margin-top: 15px;
        }

        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            resize: none;
        }

        button {
            background: #3b82f6;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: background 0.2s;
        }

        button:hover {
            background: #2563eb;
        }

        /* Section Titles */
        .section-title {
            text-align: center;
            font-size: 18px;
            color: #111827;
            margin: 25px 0 10px 0;
            font-weight: bold;
        }

        /* Top Buttons */
        .top-buttons {
            text-align: center;
            margin-bottom: 30px;
        }

        .top-buttons a {
            display: inline-block;
            background: #10b981;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            margin: 0 5px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .top-buttons a:hover {
            background: #059669;
        }
    </style>
</head>

<body>

    <h1>Process Approval Dashboard</h1>

    <div class="top-buttons">
        <a href="{{ route('approvals.create') }}">+ Create Request</a>
        <a href="{{ route('approvals.index') }}">📋 View All Requests</a>
         <a href="{{ route('approvals.pending') }}">⏳ Pending Approvals</a>
    </div>

    <!-- User Submitted Requests -->
    <div class="section-title">My Submitted Requests</div>
    <div class="container">
        @foreach($myRequests as $request)
            <div class="card">
                <h3>{{ $request->title }}</h3>
                <p>{{ $request->description }}</p>
                <p><strong>Status:</strong>
                    <span class="badge badge-{{ strtolower($request->status) }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </p>
            </div>
        @endforeach
    </div>

    

</body>

</html>