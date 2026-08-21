<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and redirect to the action URL.
     */
    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $url = $notification->data['action_url'] ?? route('tickets.index');

        return redirect($url);
    }

    /**
     * Mark all unread notifications for the user as read.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
