<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brankas extends Model
{
    protected $fillable = [
        'user_id', 'item_name', 'target_price', 'collected_amount',
        'deadline', 'priority', 'description', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}