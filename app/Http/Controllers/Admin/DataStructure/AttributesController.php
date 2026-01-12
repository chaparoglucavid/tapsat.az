<?php

namespace App\Http\Controllers\Admin\DataStructure;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::paginate(20);
        return view('admin-dashboard.data-structure.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-dashboard.data-structure.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:100',
            'type' => 'required|in:text,number,select,boolean,date',
            'is_active' => 'required|boolean',
            'options' => 'nullable|array|required_if:type,select',
            'options.*' => 'required|string', // JSON formatında gələcək: [{"az":"Benzin","en":"Petrol"}, ...]
        ]);

        try {
            $attribute = Attribute::create([
                'uuid' => Str::uuid(),
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']['en'] ?? $validated['name'][app()->getLocale()] ?? reset($validated['name'])),
                'type' => $validated['type'],
                'is_active' => $validated['is_active'],
            ]);

            if ($request->type === 'select' && $request->has('options')) {
                foreach ($request->options as $index => $optionJson) {
                    $optionValue = json_decode($optionJson, true);
                    $attribute->options()->create([
                        'value' => $optionValue,
                        'order' => $index
                    ]);
                }
            }

            notify()->success(t_db('general', 'attribute_added_successfully'));
            return redirect()->route('attributes.index');

        } catch (\Exception $e) {
            \Log::error('Attribute store failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $attribute = Attribute::where('uuid', $uuid)->with('options')->firstOrFail();
        return view('admin-dashboard.data-structure.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $attribute = Attribute::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:100',
            'type' => 'required|in:text,number,select,boolean,date',
            'is_active' => 'required|boolean',
            'options' => 'nullable|array|required_if:type,select',
        ]);

        try {
            $attribute->update([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'is_active' => $validated['is_active'],
            ]);

            if ($request->type === 'select') {
                // Mövcud opsiyaları silib yenilərini yazmaq ən sadə yoldur (və ya sync məntiqi qura bilərik)
                // Sadəlik üçün silib yenidən yaradacam (amma ID-lər dəyişəcək, diqqətli olmaq lazımdır)
                // Daha yaxşı yanaşma: gələn opsiyaların ID-si varsa update, yoxsa create.
                
                // Bu nümunədə sadə saxlayıram:
                $attribute->options()->delete();
                if ($request->has('options')) {
                    foreach ($request->options as $index => $optionJson) {
                         $optionValue = json_decode($optionJson, true);
                         // Bəzən string kimi gələ bilər, yoxlamaq lazımdır
                         if (!is_array($optionValue)) {
                             // Əgər birbaşa massiv gəlibsə (JS tərəfdən asılıdır)
                             $optionValue = $optionJson; 
                         }

                        $attribute->options()->create([
                            'value' => $optionValue,
                            'order' => $index
                        ]);
                    }
                }
            }

            notify()->success(t_db('general', 'attribute_updated_successfully'));
            return redirect()->route('attributes.index');

        } catch (\Exception $e) {
            \Log::error('Attribute update failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }
}
