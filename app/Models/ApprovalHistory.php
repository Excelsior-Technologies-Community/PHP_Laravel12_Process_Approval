<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'approver_id',
        'status',   // approved / rejected
        'comment',  // optional comment
    ];

    // The request this history belongs to
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'request_id');
    }

    // The user who approved/rejected
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}