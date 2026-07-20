<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = [];
    protected $casts = [

    ];
    public function categories(){
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
