<?php
namespace App\Services;
use App\Http\Resources\OrderResource;
use App\Models\User;
class OrderService{
    public function getOrders(User $user,array $filters){
        $query = $user->orders()
            ->with(['items.product']);
        if(!empty($filters['status']) && $filters['status'] != 'all'){
            $query->where('status',$filters['status']);
        }
        if(!empty($filters['search'])) {
            $query->where('order_number', 'like', '%' . $filters['search'] . '%');
        }
        switch ($filters['sort'] ?? null) {
            case 'oldest':
                $query->oldest('created_at');
            break;
            case 'newest':
            default:
                $query->latest('created_at');
            break;
        }
        $orders = $query->paginate(5);
        $counts = $user->orders()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        return [
            'orders' => $orders,
            'counts' => [
                'all' => $user->orders()->count(),
                'processing' => $counts->get('processing',0),
                'shipped' => $counts->get('shipped',0),
                'delivered' => $counts->get('delivered',0),
                'cancelled' => $counts->get('cancelled',0),
            ]
        ];
    }
}
