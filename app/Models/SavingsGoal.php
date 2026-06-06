<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsGoal extends Model
{
    protected $fillable = [
        'user_id', 'goal_name', 'target_amount', 
        'collected_amount', 'deadline', 'description', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}