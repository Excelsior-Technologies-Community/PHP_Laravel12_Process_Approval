<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    protected $fillable = ['request_id', 'approver_id', 'step', 'status', 'comment'];
    
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class);
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}