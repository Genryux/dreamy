<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Get notifications with pagination
            $notifications = $user->notifications()
                ->latest()
                ->paginate(20);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications->items(),
                    'unread_count' => $user->unreadNotifications()->count(),
                    'total' => $notifications->total(),
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        try {
            $user = $request->user();
            
            $notification = $user->notifications()->find($id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->markAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            $user->unreadNotifications()->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'unread_count' => $user->unreadNotifications()->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
