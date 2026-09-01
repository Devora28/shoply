<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $table = 'cart_items';
    protected $guarded = [];
    public function cart(): BelongsTo{
        return $this->belongsTo(Cart::class,'cart_id');
    }
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class,'product_id');
    }
    public function variant(): BelongsTo{
        return $this->belongsTo(ProductVariant::class,'variant_id');
    }
}
