<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status', // pending / approved / rejected
    ];

    // The user who submitted this request
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // All approval actions for this request
    public function histories()
    {
        return $this->hasMany(ApprovalHistory::class, 'request_id');
    }
}