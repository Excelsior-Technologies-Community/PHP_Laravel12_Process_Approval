<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f3f4f6;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0 20px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #374151;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #6b7280;
            margin-top: 8px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-header h2 {
            font-size: 20px;
            color: #333;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            color: #6b7280;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
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

        .view-link {
            color: #667eea;
            text-decoration: none;
        }

        .pending-item {
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        .pending-item a {
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            table, thead, tbody, th, td, tr {
                display: block;
            }
            
            th {
                display: none;
            }
            
            td {
                padding: 10px;
                border-bottom: 1px solid #e5e7eb;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">Approval System</div>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('approvals.index') }}">My Requests</a>
                <a href="{{ route('approvals.pending') }}">Pending Approvals</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #374151; cursor: pointer;">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total_requests'] }}</div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['pending_requests'] }}</div>
                <div class="stat-label">Pending Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['approved_requests'] }}</div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['rejected_requests'] }}</div>
                <div class="stat-label">Rejected</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['pending_approvals'] }}</div>
                <div class="stat-label">Pending Your Approval</div>
            </div>
        </div>

        <!-- My Requests -->
        <div class="card">
            <div class="card-header">
                <h2>My Requests</h2>
                <a href="{{ route('approvals.create') }}" class="btn-primary">+ New Request</a>
            </div>
            
            @if($myRequests->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myRequests as $request)
                        <tr>
                            <td>{{ $request->title }}</td>
                            <td>
                                <span class="status-badge status-{{ $request->status }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('approvals.show', $request->id) }}" class="view-link">View Details →</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $myRequests->links() }}
            @else
                <div class="empty-state">
                    <p>No requests yet.</p>
                    <a href="{{ route('approvals.create') }}" style="color: #667eea; margin-top: 10px; display: inline-block;">Create your first request →</a>
                </div>
            @endif
        </div>

        <!-- Pending Approvals -->
        <div class="card">
            <div class="card-header">
                <h2>Pending Your Approval</h2>
                <a href="{{ route('approvals.pending') }}" style="color: #667eea; text-decoration: none;">View All →</a>
            </div>
            
            @if($pendingApprovals->count() > 0)
                @foreach($pendingApprovals as $flow)
                    <div class="pending-item">
                        <a href="{{ route('approvals.show', $flow->request_id) }}">
                            <strong>{{ $flow->request->title }}</strong>
                            <span style="color: #6b7280; font-size: 14px;">- {{ $flow->name }}</span>
                            <div style="font-size: 12px; color: #9ca3af; margin-top: 5px;">
                                Requested by: {{ $flow->request->user->name }}
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>No pending approvals.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>