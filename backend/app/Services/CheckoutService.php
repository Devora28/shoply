<?php
namespace App\Services;
use App\Models\User;
class CheckoutService{

    public function getAddresses(User $user){
        return $user->addresses()->get();
    }
}
