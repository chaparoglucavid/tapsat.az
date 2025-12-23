<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TranslationsController extends Controller
{
    public function update(Request $request, $translationId)
    {

        try {
            $validated = $request->validate([
                'value' => 'required|string|max:255'
            ]);

            $translation =Translation::where('uuid', $translationId)
                ->first();

            if (!$translation) {
                return response()->json([
                    'success' => false,
                    'message' => t_db('general', 'translation_not_found')
                ]);
            }

            Translation::where('uuid', $translationId)
                ->update([
                    'value' => $validated['value']
                ]);

            return response()->json([
                'success' => true,
                'message' => t_db('general', 'translation_updated_successfully')
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function updateAll(Request $request)
    {
        $validated = $request->validate([
            'translations'   => ['required', 'array'],
            'translations.*' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['translations'] as $uuid => $value) {
                Translation::where('uuid', $uuid)->update(['value' => $value]);
            }
        });

        return back()->with('success', t_db('general', 'translations_updated_successfully'));
    }
}
