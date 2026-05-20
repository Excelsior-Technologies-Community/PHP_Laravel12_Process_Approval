<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalFlow;
use App\Models\ApprovalRequest;
use App\Models\ApprovalHistory;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovalStatusMail;

class ApproverController extends Controller
{
    public function pending()
    {
        $flows = ApprovalFlow::where('approver_id', auth()->id())
            ->where('status', 'pending')
            ->with('request.user')
            ->get();

        return view('approvals.pending', compact('flows'));
    }

    public function action(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'comment' => 'nullable|string'
        ]);

        $flow = ApprovalFlow::findOrFail($id);
        
        // Check if user is the assigned approver
        if ($flow->approver_id != auth()->id()) {
            abort(403);
        }

        $approvalRequest = ApprovalRequest::findOrFail($flow->request_id);

        // Save history
        ApprovalHistory::create([
            'request_id' => $flow->request_id,
            'approver_id' => auth()->id(),
            'step' => $flow->step,
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        // Update flow status
        $flow->status = $request->status;
        $flow->save();

        // If rejected, reject the entire request
        if ($request->status == 'rejected') {
            $approvalRequest->status = 'rejected';
            $approvalRequest->save();
        } 
        // If approved, check if all flows are approved
        else if ($request->status == 'approved') {
            $allApproved = ApprovalFlow::where('request_id', $flow->request_id)
                ->where('status', '!=', 'approved')
                ->doesntExist();
            
            if ($allApproved) {
                $approvalRequest->status = 'approved';
                $approvalRequest->save();
            }
        }

        // Send email to request owner
        if ($approvalRequest->user && $approvalRequest->user->email) {
            Mail::to($approvalRequest->user->email)
                ->send(new ApprovalStatusMail($approvalRequest));
        }

        return redirect()->route('approvals.pending')
            ->with('success', 'Action submitted successfully!');
    }

    public function history($id)
    {
        $request = ApprovalRequest::with('histories.approver')
            ->findOrFail($id);
        
        return view('approvals.history', compact('request'));
    }
}