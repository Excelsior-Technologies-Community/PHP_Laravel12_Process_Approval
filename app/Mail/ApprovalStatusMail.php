<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $approvalRequest;

    public function __construct($approvalRequest)
    {
        $this->approvalRequest = $approvalRequest;
    }

    public function build()
    {
        return $this->subject('Approval Request Status Updated')
            ->view('emails.approval-status');
    }
}