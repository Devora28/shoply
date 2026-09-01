<?php
namespace App\Services;
use App\Models\User;
class WishlistService{
    public function getWishlist(User $user){
        $wishlist = $user->wishlist()->firstOrCreate();
        $wishlist->load(['items.product.brand']);
        $wishlist->loadCount('items');
        return $wishlist;
    }
    public function addWishlistItem(User $user,$id){
        $wishlist = $user->wishlist()->firstOrCreate();
        $exists = $wishlist->items()
            ->where('product_id', $id)
            ->exists();
        if ($exists) {
            return false;
        }
        $wishlist->items()->create([
            'product_id'=>$id,
        ]);
        return true;
    }
    public function removeWishlistItem(User $user,$id){
        $wishlist = $user->wishlist()->firstOrCreate();
        $wishlist->items()->where('id',$id)->delete();
        return true;
    }
}
