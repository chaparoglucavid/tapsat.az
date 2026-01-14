<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\IpRule;
use Illuminate\Http\Request;

class IpRulesController extends Controller
{
    public function index()
    {
        $rules = IpRule::orderByDesc('created_at')->paginate(20);
        return view('admin-dashboard.security.ip-rules.index', compact('rules'));
    }

    public function create()
    {
        return view('admin-dashboard.security.ip-rules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'type' => 'required|string|in:blocked,allowed',
            'reason' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        IpRule::create($validated);

        notify()->success(t_db('general', 'created_successfully'));
        return redirect()->route('ip-rules.index');
    }

    public function edit(IpRule $ipRule)
    {
        return view('admin-dashboard.security.ip-rules.edit', compact('ipRule'));
    }

    public function update(Request $request, IpRule $ipRule)
    {
        $validated = $request->validate([
            'ip_address' => 'required|ip',
            'type' => 'required|string|in:blocked,allowed',
            'reason' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $ipRule->update($validated);

        notify()->success(t_db('general', 'updated_successfully'));
        return redirect()->route('ip-rules.index');
    }

    public function destroy(IpRule $ipRule)
    {
        $ipRule->delete();

        notify()->success(t_db('general', 'deleted_successfully'));
        return redirect()->route('ip-rules.index');
    }
}

