<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $userId = request()->query('userId');
        $unitId = request()->query('unitId');
        $userRole = request()->query('userRole');

        $query = Notification::orderBy('created_at', 'desc');

        if ($userRole === 'ketua') {
            $query->where('user_role', $userRole)
                    ->where('unit_id', $unitId);
        } else if ($userRole === 'admin') {
            $query->where('user_role', 'admin');
        } else {
            $query->where('anggota_unit_id', $userId);
        }

        $notifications = $query->get();
        $notificationsData = [];
        foreach ($notifications as $notification) {
            $notificationsData[] = [
                'id' => $notification->id,
                'userId' => $notification->anggota_unit_id != null ? $notification->anggota_unit_id : 0,
                'userRole' => $notification->user_role,
                'title' => $notification->title,
                'description' => $notification->description,
                'isUnRead' => $notification->is_unread == 1 ? true : false,
                'createdAt' => $notification->created_at
            ];
        }

        return response()->json(['status' => 'success', 'notifications' => $notificationsData], 200);
    }

    public function markAllAsRead()
    {
        $userId = request()->query('userId');
        $unitId = request()->query('unitId');
        $userRole = request()->query('userRole');
        $query = Notification::where('is_unread', 1);

        if ($userRole === 'ketua') {
            $query->where('user_role', $userRole)
                    ->where('unit_id', $unitId);
        } else if ($userRole === 'admin') {
            $query->where('user_role', 'admin');
        } else {
            $query->where('anggota_unit_id', $userId);
        }

        $updated = $query->update(['is_unread' => 0]);
        if ($updated > 0) {
            return response()->json(['status' => 'success', 'message' => 'All notifications marked as read'], 200);
        } else {
            return response()->json(['status' => 'success', 'message' => 'No unread notifications found'], 200);
        }
    }
}
