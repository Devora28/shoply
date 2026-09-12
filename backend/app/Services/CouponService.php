<?php
namespace App\Services;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Validation\ValidationException;
class CouponService{
    public function validate(User $user,$code,Cart $cart): Coupon{
        $coupon = Coupon::where('code',$code)->first();
        if(!$coupon){
            throw ValidationException::withMessages([
                'code' => 'The promo code is invalid'
            ]);
        }
        if(!$coupon->is_active){
            throw ValidationException::withMessages([
                'code' => 'The promo code is inactive'
            ]);
        }
        if($coupon->starts_at && now()->lt($coupon->starts_at)){
            throw ValidationException::withMessages([
                'code' => 'This promo code is not active yet'
            ]);
        }
        if($coupon->expires_at && now()->gt($coupon->expires_at)){
            throw ValidationException::withMessages([
                'code' => 'This promo code has expired'
            ]);
        }
        if($coupon->usage_limit !== null && $coupon->usage_count >= $coupon->usage_limit){
            throw ValidationException::withMessages([
                'code' => 'Promo code is no longer available'
            ]);
        }
        if($user && $coupon->per_user_limit !== null){
            $userUsageCount = $coupon->usages()->where('user_id',$user->id)->count();
            if($userUsageCount >= $coupon->per_user_limit){
                throw ValidationException::withMessages([
                    'code' => 'You have already used this promo code'
                ]);
            }
        }
        $subtotal = $this->subTotal($cart);
        if($coupon->minimum_amount !== null && $subtotal < $coupon->minimum_amount){
            throw ValidationException::withMessages([
                'code' => 'Minimum cart total not reached'
            ]);
        }
        return $coupon;
    }
    public function calculateDiscount(Cart $cart,Coupon $coupon){
        $subtotal = $this->subTotal($cart);
        if($coupon->type === 'percentage'){
            $discount = $subtotal * ($coupon->value / 100);
        } else {
            $discount = $coupon->value;
        }
        if ($coupon->maximum_discount !== null){
            $discount = min($discount,$coupon->maximum_discount);
        }
        return min($discount,$subtotal);
    }
    private function subTotal($cart){
        $cart->loadMissing([
            'items.product',
            'items.variant'
        ]);
        return $cart->items->sum(function($item){
            $productDiscount = $item->product?->discount ?? 0;
            $price = $item->variant?->price
                ?? $item->product?->price
                ?? 0;
            $finalPrice = $price - ($price * $productDiscount / 100);
            return $finalPrice * $item->quantity;
        });
    }
}
