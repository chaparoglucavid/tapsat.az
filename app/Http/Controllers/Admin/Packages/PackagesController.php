<?php

namespace App\Http\Controllers\Admin\Packages;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::paginate(20);
        return view('admin-dashboard.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-dashboard.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        try {
            Package::create([
                'uuid' => Str::uuid(),
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

            notify()->success(t_db('general', 'package_added_successfully'));
            return redirect()->route('packages.index');

        } catch (\Exception $e) {
            \Log::error('Package store failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $package = Package::where('uuid', $uuid)->firstOrFail();
        return view('admin-dashboard.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $package = Package::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        try {
            // Update Package
            $package->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

            notify()->success(t_db('general', 'package_updated_successfully'));
            return redirect()->route('packages.index');

        } catch (\Exception $e) {
            \Log::error('Package update failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            $package = Package::where('uuid', $uuid)->firstOrFail();
            $package->delete();
            
            notify()->success(t_db('general', 'package_deleted_successfully'));
            return redirect()->route('packages.index');
        } catch (\Exception $e) {
            \Log::error('Package delete failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back();
        }
    }
}
