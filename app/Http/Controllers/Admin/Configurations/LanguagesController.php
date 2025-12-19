<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::all();
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
        try {
            $validated = $request->validate([
                'code'      => 'required|string|max:10|unique:languages,code',
                'name'      => 'required|string|max:100',
                'is_active' => 'required|integer',
                'is_default'=> 'nullable'
            ]);

            DB::beginTransaction();

            if ($request->has('is_default')) {
                Language::where('is_default', true)->update([
                    'is_default' => false
                ]);
            }

            Language::create([
                'code'       => $validated['code'],
                'name'       => $validated['name'],
                'is_active'  => $validated['is_active'],
                'is_default' => $request->has('is_default')
            ]);

            DB::commit();

            notify()->success(t_db('notification', 'language_added_successfully'));
            return redirect()
                ->route('languages.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
