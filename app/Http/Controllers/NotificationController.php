<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user's notifications for dropdown or full page
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // For AJAX requests (dropdown), return limited notifications
            $notifications = Auth::user()->notifications()
                ->with('activity')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'notifications' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'activity_id' => $notification->activity_id,
                        'icon' => $notification->getIcon(),
                    ];
                }),
                'unread_count' => Auth::user()->notifications()->unread()->count()
            ]);
        }

        // For full page view, return paginated notifications
        $notifications = Auth::user()->notifications()
            ->with('activity')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        try {
            // Ensure user can only mark their own notifications as read
            if ($notification->user_id !== Auth::id()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to mark this notification as read.'
                    ], 403);
                }
                abort(403);
            }

            $notification->markAsRead();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification marked as read.',
                    'unread_count' => Auth::user()->notifications()->unread()->count()
                ]);
            }

            return redirect()->back()->with('success', 'Notification marked as read.');
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while marking the notification as read. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to mark notification as read. Please try again.');
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            Auth::user()->notifications()->unread()->update(['is_read' => true]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'All notifications marked as read.',
                    'unread_count' => 0
                ]);
            }

            return redirect()->back()->with('success', 'All notifications marked as read.');
        } catch (\Exception $e) {
            \Log::error('Error marking all notifications as read: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while marking all notifications as read. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to mark all notifications as read. Please try again.');
        }
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        try {
            // Ensure user can only delete their own notifications
            if ($notification->user_id !== Auth::id()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to delete this notification.'
                    ], 403);
                }
                abort(403);
            }

            $notification->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification deleted successfully.',
                    'unread_count' => Auth::user()->notifications()->unread()->count()
                ]);
            }

            return redirect()->back()->with('success', 'Notification deleted.');
        } catch (\Exception $e) {
            \Log::error('Error deleting notification: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the notification. Please try again.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete notification. Please try again.');
        }
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount()
    {
        return response()->json([
            'unread_count' => Auth::user()->notifications()->unread()->count()
        ]);
    }
}
