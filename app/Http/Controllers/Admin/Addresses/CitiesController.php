<?php

namespace App\Http\Controllers\Admin\Addresses;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->paginate(20);
        return view('admin-dashboard.addresses.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin-dashboard.addresses.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:cities,name',
            'is_active' => 'required|boolean',
        ]);

        try {
            City::create([
                'uuid' => Str::uuid(),
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

            notify()->success(t_db('general', 'city_added_successfully'));
            return redirect()->route('cities.index');

        } catch (\Exception $e) {
            \Log::error('City store failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    public function show(string $uuid)
    {
        $city = City::where('uuid', $uuid)->first();
        if (!$city) {
            notify()->error(t_db('general', 'city_not_found'));
            return redirect()->route('cities.index');
        }
        return view('admin-dashboard.addresses.cities.show', compact('city'));
    }

    public function edit(string $uuid)
    {
        $city = City::where('uuid', $uuid)->first();
        if (!$city) {
            notify()->error(t_db('general', 'city_not_found'));
            return redirect()->route('cities.index');
        }
        return view('admin-dashboard.addresses.cities.edit', compact('city'));
    }

    public function update(Request $request, string $uuid)
    {
        $city = City::where('uuid', $uuid)->first();
        if (!$city) {
            notify()->error(t_db('general', 'city_not_found'));
            return redirect()->route('cities.index');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:cities,name,' . $city->id,
            'is_active' => 'required|boolean',
        ]);

        try {
            $city->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

            notify()->success(t_db('general', 'city_updated_successfully'));
            return redirect()->route('cities.index');

        } catch (\Exception $e) {
            \Log::error('City update failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    public function destroy(string $uuid)
    {
        $city = City::where('uuid', $uuid)->first();

        if (!$city) {
            notify()->error(t_db('general', 'city_not_found'));
            return redirect()->route('cities.index');
        }

        try {
            $city->delete(); // Soft delete
            notify()->success(t_db('general', 'city_deleted_successfully'));
        } catch (\Exception $e) {
            \Log::error('City delete failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
        }

        return redirect()->route('cities.index');
    }
}
