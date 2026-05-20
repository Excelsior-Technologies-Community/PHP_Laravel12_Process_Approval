<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\ApprovalFlow;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = ApprovalRequest::where('user_id', auth()->id());

        // SEARCH BY TITLE
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // FILTER BY STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $myRequests = $query->latest()->paginate(10);

        $pendingApprovals = ApprovalFlow::where('approver_id', auth()->id())
            ->where('status', 'pending')
            ->with('request')
            ->get();
        
        // Statistics
        $stats = [
            'total_requests' => ApprovalRequest::where('user_id', auth()->id())->count(),
            'pending_requests' => ApprovalRequest::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'approved_requests' => ApprovalRequest::where('user_id', auth()->id())->where('status', 'approved')->count(),
            'rejected_requests' => ApprovalRequest::where('user_id', auth()->id())->where('status', 'rejected')->count(),
            'pending_approvals' => $pendingApprovals->count(),
        ];

        return view('dashboard', compact('myRequests', 'pendingApprovals', 'stats'));
    }
}