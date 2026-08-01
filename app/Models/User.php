<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'username',
        'phone',
        'password',
        'role',
        'status',
        'wallet_balance'
    ];

    protected $hidden = [
        'password',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function walletTransactions() {
        return $this->hasMany(WalletTransaction::class);
    }
}
