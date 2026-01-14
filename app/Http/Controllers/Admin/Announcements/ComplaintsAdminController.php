<?php

namespace App\Http\Controllers\Admin\Announcements;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementComplaint;
use App\Models\ComplaintSubject;
use Illuminate\Http\Request;

class ComplaintsAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = AnnouncementComplaint::query()
            ->with(['announcement.user', 'announcement.category', 'subject']);

        if ($request->filled('subject_id')) {
            $query->where('complaint_subject_id', $request->integer('subject_id'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('announcement', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $complaints = $query->latest()->paginate(20)->withQueryString();

        $subjects = ComplaintSubject::all();

        return view('admin-dashboard.announcements.complaints.index', compact('complaints', 'subjects'));
    }
}

