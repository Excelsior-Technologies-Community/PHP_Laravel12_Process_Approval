<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\ApprovalFlow;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Requests submitted by logged-in user
        $myRequests = ApprovalRequest::where('user_id', $userId)->get();

        // Pending approvals for logged-in user
        $pendingApprovals = ApprovalFlow::where('approver_id', $userId)->get();

        return view('dashboard', compact('myRequests', 'pendingApprovals'));
    }
}