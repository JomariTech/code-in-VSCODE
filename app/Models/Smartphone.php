<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smartphone extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'brands',
        'model',
        'overview',
        'processor',
        'memory',
        'display',
        'battery',
        'camera',
        'price',
        'number_of_reviews',
        'percentage_of_ratings',
    ];
    public function reviews()
    {
        return $this->hasMany(RateReviewAndComment::class);
    }
}
