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

        $myRequests = $query->latest()->get();

        $pendingApprovals = ApprovalFlow::where('approver_id', auth()->id())->get();

        return view('dashboard', compact('myRequests', 'pendingApprovals'));
    }
}