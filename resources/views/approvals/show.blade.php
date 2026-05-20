<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-button {
            margin-bottom: 20px;
        }

        .back-button a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: #667eea;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .back-button a:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .card-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .card-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-approved {
            background: #d1fae5;
            color: #059669;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 500;
        }

        .description {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .description h3 {
            margin-bottom: 10px;
            color: #374151;
        }

        .flow-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 20px;
        }

        .step-content {
            flex: 1;
        }

        .step-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .comment-box {
            margin-top: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            resize: vertical;
            font-family: inherit;
        }

        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #5a67d8;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-edit {
            background: #3b82f6;
        }

        .btn-delete {
            background: #ef4444;
        }

        @media (max-width: 768px) {
            .card {
                padding: 20px;
            }
            
            .flow-step {
                flex-direction: column;
                text-align: center;
            }
            
            .step-number {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="back-button">
            <a href="{{ url()->previous() }}">← Back</a>
        </div>

        <div class="card">
            <div class="card-header">
                <h1>{{ $request->title }}</h1>
                <span class="status-badge status-{{ $request->status }}">
                    {{ ucfirst($request->status) }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Request ID</div>
                    <div class="info-value">#{{ $request->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Requested By</div>
                    <div class="info-value">{{ $request->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Created At</div>
                    <div class="info-value">{{ $request->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ $request->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>

            <div class="description">
                <h3>Description</h3>
                <p>{{ $request->description }}</p>
            </div>

            @if($request->user_id == auth()->id() && $request->status == 'pending')
                <div class="action-buttons">
                    <a href="{{ route('approvals.edit', $request->id) }}">
                        <button class="btn-edit" style="background: #3b82f6;">Edit Request</button>
                    </a>
                    <form action="{{ route('approvals.destroy', $request->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" style="background: #ef4444;" onclick="return confirm('Are you sure?')">Delete Request</button>
                    </form>
                </div>
            @endif
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px;">Approval Flow</h3>
            @foreach($request->flows as $flow)
                <div class="flow-step">
                    <div class="step-number">{{ $flow->step }}</div>
                    <div class="step-content">
                        <strong>{{ $flow->name }}</strong><br>
                        Approver: {{ $flow->approver->name ?? 'Not Assigned' }}<br>
                        <span class="step-status status-{{ $flow->status }}">
                            {{ ucfirst($flow->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px;">Approval History</h3>
            @forelse($request->histories as $history)
                <div class="comment-box" style="margin-bottom: 15px;">
                    <strong>{{ $history->approver->name ?? 'System' }}</strong>
                    <span style="color: #6b7280; font-size: 12px;">{{ $history->created_at->format('M d, Y H:i') }}</span>
                    <div style="margin-top: 5px;">
                        Status: <span class="status-badge status-{{ $history->status }}">{{ ucfirst($history->status) }}</span>
                    </div>
                    @if($history->comment)
                        <div style="margin-top: 10px; color: #4b5563;">"{{ $history->comment }}"</div>
                    @endif
                </div>
            @empty
                <p>No history yet.</p>
            @endforelse
        </div>

        <div class="card">
            <h3 style="margin-bottom: 20px;">Comments</h3>
            @forelse($request->comments as $comment)
                <div class="comment-box" style="margin-bottom: 15px;">
                    <strong>{{ $comment->user->name }}</strong>
                    <span style="color: #6b7280; font-size: 12px;">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                    <div style="margin-top: 10px;">{{ $comment->comment }}</div>
                </div>
            @empty
                <p>No comments yet.</p>
            @endforelse

            <form action="{{ route('approvals.comment', $request->id) }}" method="POST">
                @csrf
                <textarea name="comment" rows="3" placeholder="Add a comment..."></textarea>
                <button type="submit">Post Comment</button>
            </form>
        </div>
    </div>
</body>
</html>