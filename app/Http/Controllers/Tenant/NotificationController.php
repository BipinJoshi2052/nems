<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get unread notification count.
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => 0
        ]);
    }

    /**
     * Get recent notifications.
     */
    public function index()
    {
        return response()->json([
            'notifications' => []
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }
}
