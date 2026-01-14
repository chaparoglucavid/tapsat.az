<?php

namespace App\Http\Controllers\Admin\DataStructure;

use App\Http\Controllers\Controller;
use App\Models\ComplaintSubject;
use Illuminate\Http\Request;

class ComplaintSubjectsController extends Controller
{
    public function index()
    {
        $subjects = ComplaintSubject::orderBy('name')->paginate(20);
        return view('admin-dashboard.data-structure.complaint-subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin-dashboard.data-structure.complaint-subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ComplaintSubject::create($validated);

        notify()->success(t_db('general', 'created_successfully'));
        return redirect()->route('complaint-subjects.index');
    }

    public function edit(ComplaintSubject $complaintSubject)
    {
        return view('admin-dashboard.data-structure.complaint-subjects.edit', compact('complaintSubject'));
    }

    public function update(Request $request, ComplaintSubject $complaintSubject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $complaintSubject->update($validated);

        notify()->success(t_db('general', 'updated_successfully'));
        return redirect()->route('complaint-subjects.index');
    }

    public function destroy(ComplaintSubject $complaintSubject)
    {
        $complaintSubject->delete();

        notify()->success(t_db('general', 'deleted_successfully'));
        return redirect()->route('complaint-subjects.index');
    }
}

