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
            ->whereHas('request', fn($q) => $q->where('status', 'pending'))
            ->get();

        return view('approvals.pending', compact('flows'));
    }

    public function action(Request $request, $id)
    {
        $flow = ApprovalFlow::findOrFail($id);

        $approvalRequest = ApprovalRequest::findOrFail($flow->request_id);

        // Save history
        ApprovalHistory::create([
            'request_id' => $flow->request_id,
            'approver_id' => auth()->id(),
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        // Update request status
        $approvalRequest->status = $request->status;
        $approvalRequest->save();

        // EMAIL SEND TO REQUEST OWNER
        if ($approvalRequest->user && $approvalRequest->user->email) {
            Mail::to($approvalRequest->user->email)
                ->send(new ApprovalStatusMail($approvalRequest));
        }

        return redirect()->route('approvals.pending')
            ->with('success', 'Action submitted & email sent!');
    }
}
