<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalComment extends Model
{
    protected $fillable = ['request_id', 'user_id', 'comment'];
    
    public function request()
    {
        return $this->belongsTo(ApprovalRequest::class, 'request_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}