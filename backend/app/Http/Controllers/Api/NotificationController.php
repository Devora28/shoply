<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Notifications list',
            'data' => $this->notificationService->getAllNotifications($request->user(),$request->query())
        ]);
    }
    public function markAsRead(Request $request,$id){
        $notifications = $this->notificationService->markAsRead($request->user(),$id);
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' => $notifications
        ]);
    }
    public function markAsReadAll(Request $request){
        $notifications = $this->notificationService->markAsReadAll($request->user());
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read all',
            'data' => $notifications
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        $result = $this->notificationService->delete($request->user(),$id);
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted',
            'data' => $result
        ]);
    }
}
