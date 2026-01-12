<?php

namespace App\Http\Controllers\Admin\DataStructure;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\CategoryAttribute;
use Illuminate\Http\Request;

class CategoryAttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::IsActive()->withCount('attributes')->paginate(20);
        return view('admin-dashboard.data-structure.category-attributes.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Bu metod istifadə olunmayacaq, çünki atributları kateqoriya üzərindən əlavə edəcəyik
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $category = Category::where('uuid', $uuid)->firstOrFail();
        $attributes = Attribute::IsActive()->get();
        $categoryAttributes = CategoryAttribute::where('category_uuid', $uuid)->get();

        return view('admin-dashboard.data-structure.category-attributes.edit', compact('category', 'attributes', 'categoryAttributes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $category = Category::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
            'is_required' => 'nullable|array',
            'is_filterable' => 'nullable|array',
        ]);

        try {
            // Mövcud əlaqələri silib yenidən yaradırıq (sync)
            CategoryAttribute::where('category_uuid', $uuid)->delete();

            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId) {
                    CategoryAttribute::create([
                        'category_uuid' => $uuid,
                        'attribute_id' => $attributeId,
                        'is_required' => isset($request->is_required[$attributeId]),
                        'is_filterable' => isset($request->is_filterable[$attributeId]),
                    ]);
                }
            }

            notify()->success(t_db('general', 'category_attributes_updated_successfully'));
            return redirect()->route('category-attributes.index');

        } catch (\Exception $e) {
            \Log::error('Category attributes update failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }
}
