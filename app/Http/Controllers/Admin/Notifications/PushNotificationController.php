<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PushNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushNotificationController extends Controller
{
    public function index()
    {
        $notifications = PushNotification::latest()->paginate(20);
        return view('admin-dashboard.notifications.push.index', compact('notifications'));
    }

    public function create()
    {
        $categories = Category::all(); // Or active only
        $users = User::where('type', 'user')->limit(50)->get(); // For initial load, but select2 ajax is better for many users
        return view('admin-dashboard.notifications.push.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|in:all,users,category',
            'target_users' => 'nullable|array|required_if:target_type,users',
            'target_category' => 'nullable|exists:categories,id|required_if:target_type,category',
            'deep_link' => 'nullable|string|url',
        ]);

        $targetValue = null;
        if ($request->target_type === 'users') {
            $targetValue = $request->target_users; // Array of UUIDs or IDs? Assuming IDs from form
        } elseif ($request->target_type === 'category') {
            $targetValue = $request->target_category;
        }

        PushNotification::create([
            'title' => $request->title,
            'message' => $request->message,
            'target_type' => $request->target_type,
            'target_value' => $targetValue,
            'deep_link' => $request->deep_link,
            'status' => 'draft'
        ]);

        notify()->success(t_db('general', 'notification_created_successfully'));
        return redirect()->route('push-notifications.index');
    }

    public function edit($uuid)
    {
        $notification = PushNotification::where('uuid', $uuid)->firstOrFail();
        $categories = Category::all();
        $users = User::where('type', 'user')->get(); // Optimization needed for large DB
        return view('admin-dashboard.notifications.push.edit', compact('notification', 'categories', 'users'));
    }

    public function update(Request $request, $uuid)
    {
        $notification = PushNotification::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|in:all,users,category',
            'target_users' => 'nullable|array|required_if:target_type,users',
            'target_category' => 'nullable|exists:categories,id|required_if:target_type,category',
            'deep_link' => 'nullable|string|url',
        ]);

        $targetValue = null;
        if ($request->target_type === 'users') {
            $targetValue = $request->target_users;
        } elseif ($request->target_type === 'category') {
            $targetValue = $request->target_category;
        }

        $notification->update([
            'title' => $request->title,
            'message' => $request->message,
            'target_type' => $request->target_type,
            'target_value' => $targetValue,
            'deep_link' => $request->deep_link,
        ]);

        notify()->success(t_db('general', 'notification_updated_successfully'));
        return redirect()->route('push-notifications.index');
    }

    public function send($uuid)
    {
        $notification = PushNotification::where('uuid', $uuid)->firstOrFail();
        
        // Logic to send notification (e.g., via Firebase)
        // For now, just mark as sent
        
        $notification->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        notify()->success(t_db('general', 'notification_sent_successfully'));
        return back();
    }

    public function destroy($uuid)
    {
        $notification = PushNotification::where('uuid', $uuid)->firstOrFail();
        $notification->delete();

        notify()->success(t_db('general', 'notification_deleted_successfully'));
        return back();
    }
}
