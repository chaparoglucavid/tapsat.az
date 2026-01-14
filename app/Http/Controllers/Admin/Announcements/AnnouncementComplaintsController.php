<?php

namespace App\Http\Controllers\Admin\Announcements;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementComplaint;
use Illuminate\Http\Request;

class AnnouncementComplaintsController extends Controller
{
    public function store(Request $request, string $uuid)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'complaint_subject_id' => 'required|exists:complaint_subjects,id',
            'message' => 'nullable|string',
        ]);

        AnnouncementComplaint::create([
            'announcement_id' => $announcement->id,
            'complaint_subject_id' => $validated['complaint_subject_id'],
            'message' => $validated['message'] ?? null,
        ]);

        notify()->success(t_db('general', 'complaint_submitted_successfully'));

        return redirect()->back();
    }
}

