<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Requests created by this user
    public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class, 'user_id');
    }

    // Approval steps assigned to this user
    public function approvalFlows()
    {
        return $this->hasMany(ApprovalFlow::class, 'approver_id');
    }

    // Approvals this user has acted on
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class, 'approver_id');
    }
}