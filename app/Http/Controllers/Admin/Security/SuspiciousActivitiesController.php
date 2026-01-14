<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SuspiciousActivitiesController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()
            ->where('log_name', 'security')
            ->where('event', 'login_failed')
            ->latest();

        if ($request->filled('email')) {
            $email = $request->get('email');
            $query->whereJsonContains('properties->email', $email);
        }

        if ($request->filled('ip')) {
            $ip = $request->get('ip');
            $query->where('properties->ip', 'like', "%{$ip}%");
        }

        $activities = $query->paginate(20)->withQueryString();

        return view('admin-dashboard.security.suspicious-activities.index', compact('activities'));
    }
}

