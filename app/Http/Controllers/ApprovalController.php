<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\ApprovalFlow;
use App\Models\ApprovalHistory;
use App\Models\ApprovalComment;

class ApprovalController extends Controller
{
    public function index()
    {
        $requests = ApprovalRequest::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return view('approvals.index', compact('requests'));
    }

    public function create()
    {
        return view('approvals.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $approval = ApprovalRequest::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        // Create approval flow (multiple approvers example)
        $approvers = [2, 3, 1]; // Approver user IDs - you can modify this
        
        foreach ($approvers as $index => $approverId) {
            ApprovalFlow::create([
                'request_id' => $approval->id,
                'name' => 'Level ' . ($index + 1) . ' Approval',
                'approver_id' => $approverId,
                'step' => $index + 1,
                'status' => 'pending'
            ]);
        }

        return redirect()->route('approvals.index')
            ->with('success', 'Request submitted successfully');
    }

    public function show($id)
    {
        $request = ApprovalRequest::with(['user', 'flows.approver', 'histories.approver', 'comments.user'])
            ->findOrFail($id);
        
        // Check if user owns this request or is an approver
        if ($request->user_id != auth()->id() && !$this->isApprover($id)) {
            abort(403);
        }
        
        return view('approvals.show', compact('request'));
    }

    public function edit($id)
    {
        $request = ApprovalRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);
        
        return view('approvals.edit', compact('request'));
    }

    public function update(Request $request, $id)
    {
        $approvalRequest = ApprovalRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $approvalRequest->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        
        return redirect()->route('approvals.show', $id)
            ->with('success', 'Request updated successfully');
    }

    public function destroy($id)
    {
        $approvalRequest = ApprovalRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($id);
        
        // Delete related records
        ApprovalFlow::where('request_id', $id)->delete();
        ApprovalHistory::where('request_id', $id)->delete();
        ApprovalComment::where('request_id', $id)->delete();
        $approvalRequest->delete();
        
        return redirect()->route('approvals.index')
            ->with('success', 'Request deleted successfully');
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);
        
        ApprovalComment::create([
            'request_id' => $id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);
        
        return redirect()->route('approvals.show', $id)
            ->with('success', 'Comment added successfully');
    }

    private function isApprover($requestId)
    {
        return ApprovalFlow::where('request_id', $requestId)
            ->where('approver_id', auth()->id())
            ->exists();
    }
}