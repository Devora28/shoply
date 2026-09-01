<?php
namespace App\Services;
use App\Http\Resources\ProductCardResource;
use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Models\User;
class AccountDashboardService{
    public function getDashboardData(User $user){
        $user->load([
            'orders.items.product' => fn($q) => $q
                ->take(3)
                ->latest(),
            'wishlist' => fn($q) => $q->withCount('items'),
        ]);
        $categoryIds = $user->orders
            ->flatMap(fn($order) => $order->items)
            ->map(fn($item) => $item->product?->category_id)
            ->filter()
            ->unique();
        $recommendedProducts = Product::whereIn('category_id', $categoryIds)
            ->with([
                'brand',
            ])
            ->latest()
            ->take(8)
            ->get();
        return [
            'user' => new UserResource($user),
            'recommendedProducts' => ProductCardResource::collection($recommendedProducts),
        ];
    }
}
