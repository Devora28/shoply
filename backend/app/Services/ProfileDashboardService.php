<?php
namespace App\Services;
use App\Models\User;
class ProfileDashboardService{
    public function getProfileDashboardData(User $user){
        $user->load([
            'orders.items' => fn($q) => $q
                ->take(3)
                ->latest(),
            'notifications' => fn($q) => $q
                ->wherePivotNull('read_at')
                ->latest(),
        ]);
        return $user;
    }
}
