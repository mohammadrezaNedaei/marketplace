<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardTransferRequest extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'amount',
        'receipt_image',
        'tracking_code',
        'status',
        'admin_note',
        'reviewed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
