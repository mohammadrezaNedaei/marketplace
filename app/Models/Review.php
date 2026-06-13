<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'answer_to_id',
        'rating',
        'comment',
        'approved',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Review::class, 'answer_to_id');
    }

    public function replies()
    {
        return $this->hasMany(Review::class, 'answer_to_id');
    }
}
