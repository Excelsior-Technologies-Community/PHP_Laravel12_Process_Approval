<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\ApprovalFlow;

class ApprovalController extends Controller
{
    public function index()
    {
        $requests = ApprovalRequest::with('user')->get();
        return view('approvals.index', compact('requests'));
    }

    public function create()
    {
        return view('approvals.create');
    }

    public function store(Request $request)
    {
        $approval = ApprovalRequest::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        ApprovalFlow::create([
            'request_id' => $approval->id,
            'name' => 'Manager Approval',
            'approver_id' => 1,
            'step' => 1
        ]);

        return redirect()->route('approvals.index')
            ->with('success', 'Request submitted successfully');
    }
}