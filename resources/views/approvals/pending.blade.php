<!DOCTYPE html>
<html>

<head>
    <title>Pending Approvals</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin: 0 0 10px 0;
        }

        .card p {
            color: #555;
        }

        form {
            margin-top: 15px;
        }

        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 70px;
        }

        button {
            margin-top: 12px;
            padding: 10px 18px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background: #45a049;
        }

        .empty {
            text-align: center;
            color: #888;
            font-size: 18px;
        }
    </style>

</head>

<body>

    <h2>Pending Approvals</h2>

    @forelse($flows as $flow)

        <div class="card">

            <h3>{{ $flow->request->title }} (Step {{ $flow->step }})</h3>

            <p>Request ID: {{ $flow->request_id }}</p>

            <form action="{{ route('approvals.action', $flow->id) }}" method="POST">
                @csrf

                <select name="status">
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                </select>

                <textarea name="comment" placeholder="Add comment"></textarea>

                <button type="submit">Submit</button>

            </form>

        </div>

    @empty

        <p class="empty">No pending approvals.</p>

    @endforelse

</body>

</html>