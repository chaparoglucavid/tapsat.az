<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LanguagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::orderBy('name')->paginate(20);
        return view('admin-dashboard.configurations.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin-dashboard.configurations.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:languages,code',
            'name' => 'required|string|max:100',
            'is_active' => 'required|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $request) {

            $defaultLanguage = Language::where('is_default', true)->first();

            if ($request->boolean('is_default')) {
                Language::where('is_default', true)->update([
                    'is_default' => false
                ]);
            }

            $language = Language::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
                'is_default' => $request->boolean('is_default'),
            ]);

            if ($defaultLanguage) {
                $translations = Translation::where('locale', $defaultLanguage->code)
                    ->get(['group', 'key', 'value']);

                $now = now();

                $insertData = $translations->map(function ($t) use ($language, $now) {
                    return [
                        'uuid' => Str::uuid(),
                        'locale' => $language->code,
                        'group' => $t->group,
                        'key' => $t->key,
                        'value' => $t->value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                })->toArray();

                Translation::insert($insertData);
            }
        });

        notify()->success(t_db('general', 'language_added_successfully'));

        return redirect()->route('languages.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $language = Language::where('uuid', $uuid)->first();
        if (!$language) {
            notify(t_db('general', 'language_not_fount'), t_db('general', 'error'));
            return redirect()->back();
        }

        return view('admin-dashboard.configurations.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        try {
            $language = Language::where('uuid', $uuid)->firstOrFail();

            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
                'name' => 'required|string|max:100',
                'is_active' => 'required|boolean',
                'is_default' => 'nullable',
            ]);

            DB::transaction(function () use ($validated, $request, $language) {

                $oldCode = $language->code;
                $newCode = $validated['code'];
                $makeDefault = $request->is_default === "on" ? true : false;

                if ($makeDefault && !$language->is_default) {
                    Language::where('is_default', true)
                        ->where('uuid', '!=', $language->uuid)
                        ->update(['is_default' => false]);
                }

                $language->update([
                    'code' => $newCode,
                    'name' => $validated['name'],
                    'is_active' => $validated['is_active'],
                    'is_default' => $makeDefault,
                ]);

                if ($oldCode !== $newCode) {
                    Translation::where('locale', $oldCode)
                        ->update(['locale' => $newCode]);
                }
            });

            notify()->success(t_db('general', 'language_updated_successfully'));

            return redirect()->route('languages.index');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            notify()->error(t_db('general', 'language_not_found'));
            return redirect()->back();

        } catch (\Throwable $e) {
            report($e);
            notify()->error(t_db('general', 'something_went_wrong'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            $language = Language::where('uuid', $uuid)->first();

            if (!$language) {
                return response()->json([
                    'message' => t_db('general', 'language_not_found')
                ], 404);
            }

            if ($language->is_default) {
                return response()->json([
                    'message' => t_db('general', 'default_language_cannot_be_deleted')
                ], 422);
            }

            if (method_exists($language, 'translations')) {
                $language->translations()->delete();
            }

            $language->delete();

            return response()->json([
                'message' => t_db('general', 'language_deleted_successfully')
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Language deletion failed: ' . $e->getMessage());

            return response()->json([
                'message' => t_db('general', 'something_went_wrong')
            ], 500);
        }
    }

    /**
     * Set the given language as default.
     */
    public function setDefault(Language $language)
    {
        try {
            DB::beginTransaction();
            Language::where('is_default', true)->update(['is_default' => false]);
            $language->is_default = true;
            $language->save();

            DB::commit();

            App::setLocale($language->code);
            Session::put('locale', $language->code);

            notify()->success(t_db('general', 'language_selected_as_current'));
            return redirect()->route('languages.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            notify()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function translations($uid)
    {
        $language = Language::where('uuid', $uid)->first();
        if (!$language) {
            notify()->error(t_db('general', 'language_not_found'));
            return redirect()->back();
        }
        $translations = $language->translations()->paginate(20);

        return view(
            'admin-dashboard.configurations.languages.translations',
            compact('language', 'translations')
        );
    }

}


