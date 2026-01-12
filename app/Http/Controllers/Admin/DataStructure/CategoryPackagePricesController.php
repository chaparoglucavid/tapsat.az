<?php

namespace App\Http\Controllers\Admin\DataStructure;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Package;
use Illuminate\Http\Request;

class CategoryPackagePricesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::IsActive()->with('packages')->withCount('children')->paginate(20);
        $packages = Package::IsActive()->get();
        return view('admin-dashboard.data-structure.category-package-prices.index', compact('categories', 'packages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $category = Category::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'package_prices' => 'nullable|array',
            'package_prices.*' => 'nullable|numeric|min:0',
        ]);

        try {
            // Sync Package Prices
            $pivotData = [];
            if ($request->has('package_prices')) {
                foreach ($request->package_prices as $packageUuid => $price) {
                    if (!is_null($price) && $price !== '') {
                        $pivotData[$packageUuid] = ['price' => $price];
                    }
                }
            }
            
            $category->packages()->sync($pivotData);

            notify()->success(t_db('general', 'prices_updated_successfully'));
            return redirect()->route('category-package-prices.index');

        } catch (\Exception $e) {
            \Log::error('Category package price update failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }
}
