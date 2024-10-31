<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateReviewAndComment extends Model
{
    use HasFactory;

    
    protected $table = 'rate_review_and_comments'; 

    protected $fillable = [
        'username', 
        'rating', 
        'comment', 
        'smartphone_id'
    ];

    // Define the relationship with Smartphone
    public function smartphone()
    {
        return $this->belongsTo(Smartphone::class);
    }
}
