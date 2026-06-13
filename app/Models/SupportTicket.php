<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'subject',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // یک تیکت می‌تواند چندین پیام داشته باشد
    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }
}
