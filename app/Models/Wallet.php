<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type', 'balance', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function transfersFrom()
    {
        return $this->hasMany(WalletTransfer::class, 'from_wallet_id');
    }

    public function transfersTo()
    {
        return $this->hasMany(WalletTransfer::class, 'to_wallet_id');
    }
}