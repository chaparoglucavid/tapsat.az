<?php

namespace App\Http\Controllers\Admin\Announcements;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementAttributeValue;
use App\Models\AnnouncementImage;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::with(['category', 'city', 'user'])->latest()->paginate(20);
        return view('admin-dashboard.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::IsActive()->get();
        $cities = City::IsActive()->get();
        $users = User::all(); // Admin panel olduğu üçün user seçimi
        return view('admin-dashboard.announcements.create', compact('categories', 'cities', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'user_id' => 'required|exists:users,id',
            'is_new' => 'boolean',
            'has_delivery' => 'boolean',
            'status' => 'required|in:pending,active,rejected,expired,sold',
            'attributes' => 'nullable|array', // Dinamik atributlar
            'images' => 'nullable|array',
            'images.*' => 'string',
        ]);

        try {
            $announcement = Announcement::create([
                'uuid' => Str::uuid(),
                'user_id' => $validated['user_id'],
                'category_id' => $validated['category_id'],
                'city_id' => $validated['city_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'is_new' => $request->has('is_new'),
                'has_delivery' => $request->has('has_delivery'),
                'status' => $validated['status'],
                'published_at' => $validated['status'] === 'active' ? now() : null,
            ]);

            // Atribut dəyərlərini yadda saxla
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $value) {
                    // Dəyər boşdursa yazmayaq
                    if (empty($value)) continue;

                    AnnouncementAttributeValue::create([
                        'announcement_id' => $announcement->id,
                        'attribute_id' => $attributeId,
                        'value' => is_array($value) ? json_encode($value) : $value, // Multiselect olsa JSON saxlayacaq
                        // Əgər select tipdirsə və ID gəlirsə option_id-ə yaza bilərik, hələlik value saxlayırıq
                    ]);
                }
            }

            // Handle Images
            if ($request->has('images')) {
                foreach ($request->images as $index => $imageName) {
                    if (Storage::disk('public')->exists('tmp/' . $imageName)) {
                         $newPath = 'announcements/' . $announcement->id . '/' . $imageName;
                         Storage::disk('public')->move('tmp/' . $imageName, $newPath);
                         
                         AnnouncementImage::create([
                             'announcement_id' => $announcement->id,
                             'path' => $newPath,
                             'is_main' => $index === 0, // First image is main
                             'order' => $index
                         ]);
                    }
                }
            }

            notify()->success(t_db('general', 'announcement_added_successfully'));
            return redirect()->route('announcements.index');

        } catch (\Exception $e) {
            \Log::error('Announcement store failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();
        $categories = Category::IsActive()->get();
        $cities = City::IsActive()->get();
        $users = User::all();

        return view('admin-dashboard.announcements.edit', compact('announcement', 'categories', 'cities', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'is_new' => 'boolean',
            'has_delivery' => 'boolean',
            'status' => 'required|in:pending,active,rejected,expired,sold',
        ]);

        try {
            $announcement->update([
                'category_id' => $validated['category_id'],
                'city_id' => $validated['city_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'is_new' => $request->has('is_new'),
                'has_delivery' => $request->has('has_delivery'),
                'status' => $validated['status'],
            ]);

            // Atribut yeniləməsi hələlik sadə saxlayıram, çünki kateqoriya dəyişərsə atributlar da dəyişir.
            // Bu hissə daha mürəkkəb JS tələb edir (AJAX ilə atributları yükləmək).

            notify()->success(t_db('general', 'announcement_updated_successfully'));
            return redirect()->route('announcements.index');

        } catch (\Exception $e) {
            \Log::error('Announcement update failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        try {
            $announcement = Announcement::where('uuid', $uuid)->firstOrFail();
            $announcement->delete();
            
            notify()->success(t_db('general', 'announcement_deleted_successfully'));
            return redirect()->route('announcements.index');
        } catch (\Exception $e) {
            \Log::error('Announcement delete failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back();
        }
    }
}
