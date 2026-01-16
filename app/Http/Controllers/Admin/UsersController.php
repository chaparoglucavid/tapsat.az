<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserType;
use App\Enums\AnnouncementStatus;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::isUser()->latest()->paginate(20);
        return view('admin-dashboard.users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $user = User::with(['announcements', 'creditCards', 'payments'])->where('uuid', $uuid)->firstOrFail();
        return view('admin-dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        return view('admin-dashboard.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|unique:users,phone_number,' . $user->id,
        ]);

        $user->update($validated);

        notify()->success(t_db('general', 'user_updated_successfully'));
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->delete();

        notify()->success(t_db('general', 'user_deleted_successfully'));
        return redirect()->route('users.index');
    }

    public function ban(Request $request, string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        
        if ($user->type === UserType::ADMIN) {
            return response()->json(['message' => t_db('general', 'cannot_ban_admin')], 403);
        }

        $duration = $request->input('duration');
        
        if ($duration === 'permanent') {
            $user->update(['banned_until' => now()->addYears(100)]);
        } else {
            $days = (int) $duration;
            if ($days > 0) {
                $user->update(['banned_until' => now()->addDays($days)]);
            }
        }

        // Deactivate all active announcements
        $user->announcements()->where('status', AnnouncementStatus::ACCEPTED->value)->update([
            'status' => AnnouncementStatus::INACTIVE->value
        ]);

        return response()->json(['message' => t_db('general', 'user_banned_successfully')]);
    }

    public function unban(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->update(['banned_until' => null]);

        // Optional: We might want to reactivate announcements or leave them inactive for manual review
        // For now, let's leave them as is, or we can restore them if needed. 
        // Usually, unbanning doesn't automatically republish content to avoid spam.

        return response()->json(['message' => t_db('general', 'user_unbanned_successfully')]);
    }
}
