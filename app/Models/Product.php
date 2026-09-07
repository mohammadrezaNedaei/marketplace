<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'category_id',
        'picture_url',
        'title',
        'description',
        'price',
        'discount_price',
        'file_url',
        'status',
        'views',
        'sales_count',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function verifiedReviews()
    {
        return $this->reviews()
            ->where('approved', true)
            ->whereNull('answer_to_id')
            ->where('verified_purchase', true)
            ->whereNotNull('rating');
    }

    public function verifiedAverageRating(): ?float
    {
        $average = $this->verifiedReviews()->avg('rating');
        return $average !== null ? (float) $average : null;
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }
}
