<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('tmp', $filename, 'public');

            return response()->json([
                'name' => $filename,
                'path' => $path,
                'url' => Storage::url($path),
            ]);
        }

        return response()->json(['error' => 'File not found'], 400);
    }

    public function revert(Request $request)
    {
        $filename = $request->getContent();
        if ($filename) {
            Storage::disk('public')->delete('tmp/' . $filename);
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Filename required'], 400);
    }
}
