<?php

namespace App\Http\Controllers\Admin\Addresses;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Region;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegionsController extends Controller
{
    public function index()
    {
        $regions = Region::orderBy('name')->paginate(20);
        return view('admin-dashboard.addresses.regions.index', compact('regions'));
    }

    public function create()
    {
        $cities = City::IsActive()->get();
        return view('admin-dashboard.addresses.regions.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_uuid' => 'required|string',
            'name' => 'required|array',
            'name.*' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        try {
            Region::create([
                'uuid' => Str::uuid(),
                'city_uuid' => $validated['city_uuid'],
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

            notify()->success(t_db('general', 'region_added_successfully'));
            return redirect()->route('regions.index');

        } catch (\Exception $e) {
            \Log::error('Region store failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    public function show(string $uuid)
    {
        $region = Region::where('uuid', $uuid)->first();
        if (!$region) {
            notify()->error(t_db('general', 'region_not_found'));
            return redirect()->route('regions.index');
        }
        return view('admin-dashboard.addresses.regions.show', compact('region'));
    }

    public function edit(string $uuid)
    {
        $region = Region::where('uuid', $uuid)->first();
        if (!$region) {
            notify()->error(t_db('general', 'region_not_found'));
            return redirect()->route('regions.index');
        }

        $cities = City::IsActive()->get();
        return view('admin-dashboard.addresses.regions.edit', compact('region', 'cities'));
    }

   public function update(Request $request, string $uuid)
    {
        $region = Region::where('uuid', $uuid)->first();

        if (! $region) {
            notify()->error(t_db('general', 'region_not_found'));
            return redirect()->route('regions.index');  
        }

        $languages = Language::where('is_active', true)->get();

        if ($languages->isEmpty()) {
            notify()->error('No active languages found');
            return back();
        }

        $rules = [
            'city_uuid' => 'required|string',
            'is_active' => 'required|boolean',
        ];

        foreach ($languages as $language) {
            $rules["name.{$language->code}"] = [
                'required',
                'string',
                'max:100',

                Rule::unique('regions', "name->{$language->code}")
                    ->ignore($region->uuid),
            ];
        }

        $validated = $request->validate($rules);

        try {
            $region->update([
                'name' => $validated['name'],
                'city_uuid' => $validated['city_uuid'],
                'is_active' => $validated['is_active'],
            ]);

            notify()->success(t_db('general', 'region_updated_successfully'));
            return redirect()->route('regions.index');

        } catch (\Throwable $e) {
            \Log::error('City update failed', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    public function destroy(string $uuid)
    {
        $region = Region::where('uuid', $uuid)->first();

        if (!$region) {
            notify()->error(t_db('general', 'region_not_found'));
            return redirect()->route('regions.index');
        }

        try {
            $region->delete(); // Soft delete
            notify()->success(t_db('general', 'region_deleted_successfully'));
        } catch (\Exception $e) {
            \Log::error('Region delete failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
        }

        return redirect()->route('regions.index');
    }
}
