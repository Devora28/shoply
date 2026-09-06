<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReviewVote extends Model
{
    protected $table = 'product_review_votes';
    protected $guarded = [];
    protected $casts = [
        'is_helpful' => 'boolean',
    ];
    public function review(){
        return $this->belongsTo(ProductReview::class,'review_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
