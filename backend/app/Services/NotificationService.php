<?php
namespace App\Services;
use App\Models\Notification;
use App\Models\User;
class NotificationService{
    public function getAllNotifications(User $user,$filters){
        $query = $user->notifications();
        if(!empty($filters['type']) && $filters['type'] != 'all'){
            if($filters['type'] === 'unread') {
                $query->where('user_id',$user->id)
                    ->whereNull('read_at');
            }
            elseif($filters['type'] === 'global') {
                $query->whereNull('user_id');
            }
            else {
                $query->where('type', $filters['type']);
            }
        }
        $notifications = $query->paginate(5);
        $unreadCount = Notification::where('user_id',$user->id)->where('read_at',null)->count();
        return [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ];
    }
}
