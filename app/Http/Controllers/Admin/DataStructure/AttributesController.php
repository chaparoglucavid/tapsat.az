<?php

namespace App\Http\Controllers\Admin\DataStructure;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            DB::beginTransaction();

            $nameForSlug = $validated['name']['en'] ?? $validated['name'][app()->getLocale()] ?? reset($validated['name']);
            $slug = Str::slug($nameForSlug);
            $originalSlug = $slug;
            $count = 1;
            while (Attribute::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $attribute = Attribute::create([
                // 'uuid' => Str::uuid(), // Model boot method handles UUID generation
                'name' => $validated['name'],
                'slug' => $slug,
                'type' => $validated['type'],
                'is_active' => $validated['is_active'],
            ]);

            if ($request->type === 'select' && $request->has('options')) {
                foreach ($request->options as $index => $optionJson) {
                    $optionData = json_decode($optionJson, true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        continue; // Skip invalid JSON
                    }

                    // Support new format {value: {...}, order: 0} and fallback to old format
                    $value = isset($optionData['value']) ? $optionData['value'] : $optionData;
                    $order = isset($optionData['order']) ? $optionData['order'] : $index;

                    $attribute->options()->create([
                        'value' => $value,
                        'order' => $order
                    ]);
                }
            }

            DB::commit();

            notify()->success(t_db('general', 'attribute_added_successfully'));
            return redirect()->route('attributes.index');

        } catch (\Exception $e) {
            DB::rollBack();
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
                $existingIds = $attribute->options()->pluck('id')->toArray();
                $keptIds = [];

                if ($request->has('options')) {
                    foreach ($request->options as $index => $optionJson) {
                        $optionData = json_decode($optionJson, true);
                        
                        // Support new format {id: 1, value: {...}, order: 0} and fallback
                        $id = $optionData['id'] ?? null;
                        $value = $optionData['value'] ?? $optionData;
                        $order = $optionData['order'] ?? $index;

                        if ($id && in_array($id, $existingIds)) {
                            // Update existing option
                            $attribute->options()->where('id', $id)->update([
                                'value' => $value,
                                'order' => $order
                            ]);
                            $keptIds[] = $id;
                        } else {
                            // Create new option
                            $newOption = $attribute->options()->create([
                                'value' => $value,
                                'order' => $order
                            ]);
                            $keptIds[] = $newOption->id;
                        }
                    }
                }
                
                // Delete removed options (only ones that were not in the submitted list)
                // This preserves IDs for existing options and avoids breaking foreign keys
                if (!empty($existingIds)) {
                    $attribute->options()->whereIn('id', $existingIds)->whereNotIn('id', $keptIds)->delete();
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
