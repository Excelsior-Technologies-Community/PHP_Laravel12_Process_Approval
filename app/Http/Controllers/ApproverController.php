<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalFlow;
use App\Models\ApprovalRequest;
use App\Models\ApprovalHistory;

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

        ApprovalHistory::create([
            'request_id' => $flow->request_id,
            'approver_id' => auth()->id(),
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        $approvalRequest = ApprovalRequest::findOrFail($flow->request_id);
        $approvalRequest->status = $request->status;
        $approvalRequest->save();

        return redirect()->route('approvals.pending')->with('success', 'Action submitted!');
    }
}