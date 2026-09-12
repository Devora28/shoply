<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $guarded = [];
    public function usages() {
        return $this->hasMany(CouponUsage::class,'coupon_id');
    }
}
