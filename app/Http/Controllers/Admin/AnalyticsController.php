<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Payment;
use App\Models\Store;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function general()
    {
        $totalUsers = User::count();
        $totalStores = Store::count();
        $totalAnnouncements = Announcement::count();
        $totalRevenue = Payment::sum('amount'); // Assuming 'amount' column exists
        
        $recentActivities = Activity::with('causer')->latest()->take(10)->get();

        return view('admin-dashboard.analytics.general', compact(
            'totalUsers', 
            'totalStores', 
            'totalAnnouncements', 
            'totalRevenue', 
            'recentActivities'
        ));
    }

    public function user()
    {
        $totalUsers = User::count();
        $newUsersLast30Days = User::where('created_at', '>=', now()->subDays(30))->count();
        
        // Group by user type/role if available, assuming 'type' or checking relationships
        // For now, let's just differentiate between regular users and store owners
        $usersWithStores = Store::distinct('user_id')->count();
        $regularUsers = $totalUsers - $usersWithStores;

        // User registration trend for the last 12 months
        $userTrend = User::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"), DB::raw("MONTH(created_at) as month"))
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month_name', 'month')
            ->orderBy('month')
            ->get();

        return view('admin-dashboard.analytics.users', compact(
            'totalUsers',
            'newUsersLast30Days',
            'usersWithStores',
            'regularUsers',
            'userTrend'
        ));
    }

    public function announcement()
    {
        $totalAnnouncements = Announcement::count();
        $activeAnnouncements = Announcement::where('status', 'accepted')->count();
        $pendingAnnouncements = Announcement::where('status', 'pending')->count();
        $rejectedAnnouncements = Announcement::where('status', 'rejected')->count();
        $expiredAnnouncements = Announcement::where('status', 'expired')->count();

        $announcementsByCategory = DB::table('announcements')
            ->join('categories', 'announcements.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('count(announcements.id) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('admin-dashboard.analytics.announcements', compact(
            'totalAnnouncements',
            'activeAnnouncements',
            'pendingAnnouncements',
            'rejectedAnnouncements',
            'expiredAnnouncements',
            'announcementsByCategory'
        ));
    }

    public function income()
    {
        $totalIncome = Payment::sum('amount');
        $incomeLast30Days = Payment::where('created_at', '>=', now()->subDays(30))->sum('amount');
        
        // Income trend for the last 12 months
        $incomeTrend = Payment::select(
                DB::raw("SUM(amount) as total_amount"), 
                DB::raw("MONTHNAME(created_at) as month_name"), 
                DB::raw("MONTH(created_at) as month")
            )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month_name', 'month')
            ->orderBy('month')
            ->get();

        $recentTransactions = Payment::with('user')->latest()->take(20)->get();

        return view('admin-dashboard.analytics.income', compact(
            'totalIncome',
            'incomeLast30Days',
            'incomeTrend',
            'recentTransactions'
        ));
    }

    public function activity()
    {
        $activities = Activity::with('causer')->latest()->paginate(20);

        return view('admin-dashboard.analytics.activity', compact('activities'));
    }
}
