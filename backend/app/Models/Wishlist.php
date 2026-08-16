<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlists';
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function items(){
        return $this->hasMany(WishlistItem::class, 'wishlist_id');
    }
}
