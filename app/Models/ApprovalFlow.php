<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalFlow extends Model
{
    protected $fillable = [
        'request_id',
        'name',
        'approver_id',
        'step'
    ];

    // Relationship with ApprovalRequest
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'request_id');
    }


}