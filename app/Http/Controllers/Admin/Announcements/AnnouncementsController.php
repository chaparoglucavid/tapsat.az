<?php

namespace App\Http\Controllers\Admin\Announcements;

use App\Enums\AnnouncementStatus;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementImage;
use App\Models\AnnouncementPackage;
use App\Models\Category;
use App\Models\City;
use App\Models\ComplaintSubject;
use App\Models\Package;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;

class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::with(['category', 'city', 'user', 'activePackages'])->latest()->paginate(20);
        return view('admin-dashboard.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::IsActive()->get();
        $cities = City::IsActive()->get();
        $users = User::IsUser()->get();
        $stores = Store::Confirmed()->get();
        $packages = Package::IsActive()->get();
        return view('admin-dashboard.announcements.create', compact('categories', 'cities', 'users', 'stores', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'owner_type' => 'required|in:user,store',
            'user_id' => 'required_if:owner_type,user|nullable|exists:users,id',
            'store_id' => 'required_if:owner_type,store|nullable|exists:stores,id',
            'status' => ['required', new Enum(AnnouncementStatus::class)],
            'images' => 'nullable|array',
            'images.*' => 'string',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:packages,id'
        ]);

        // Determine user_id from store if owner_type is store
        $userId = $validated['user_id'] ?? null;
        $storeId = $validated['store_id'] ?? null;

        if ($request->owner_type === 'store' && $storeId) {
            $store = Store::find($storeId);
            $userId = $store->user_id;
        }

        try {
            DB::beginTransaction();
            $announcement = Announcement::create([
                'uuid' => Str::uuid(),
                'user_id' => $userId,
                'store_id' => $storeId,
                'category_id' => $validated['category_id'],
                'city_id' => $validated['city_id'],
                'title' => NULL,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'is_new' => $request->has('is_new'),
                'has_delivery' => $request->has('has_delivery'),
                'status' => $validated['status'],
                'published_at' => $validated['status'] === AnnouncementStatus::ACCEPTED->value ? now() : null,
                'expires_at' => $validated['status'] === AnnouncementStatus::ACCEPTED->value ? now()->addDays(30) : null,
            ]);

            if ($request->has('packages')) {
                foreach ($request->packages as $packageId) {
                    $package = Package::find($packageId);
                    if ($package) {
                        AnnouncementPackage::create([
                            'announcement_id' => $announcement->id,
                            'package_id' => $package->id,
                            'starts_at' => now(),
                            'ends_at' => now()->addDays($package->duration_days),
                        ]);
                    }
                }
            }

            

            if ($request->has('images')) {
                foreach ($request->images as $index => $imageName) {
                    if (Storage::disk('public')->exists('tmp/' . $imageName)) {
                         $newPath = 'announcements/' . $announcement->id . '/' . $imageName;
                         Storage::disk('public')->move('tmp/' . $imageName, $newPath);
                         
                         AnnouncementImage::create([
                             'announcement_id' => $announcement->id,
                             'path' => $newPath,
                             'is_main' => $index === 0,
                             'order' => $index
                         ]);
                    }
                }
            }

            DB::commit();

            notify()->success(t_db('general', 'announcement_added_successfully'));
            return redirect()->route('announcements.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Announcement store failed: ' . $e->getMessage());
            notify()->error(t_db('general', 'something_went_wrong'));
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $announcement = Announcement::with(['category', 'city', 'user', 'images', 'complaints.subject'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $complaintSubjects = ComplaintSubject::all();

        return view('admin-dashboard.announcements.show', compact('announcement', 'complaintSubjects'));
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
        $packages = Package::IsActive()->get();

        return view('admin-dashboard.announcements.edit', compact('announcement', 'categories', 'cities', 'users', 'packages'));
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
            'status' => ['required', new Enum(AnnouncementStatus::class)],
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
            'packages' => 'nullable|array',
            'packages.*' => 'exists:packages,id'
        ]);

        $rejectionReason = null;
        if ($validated['status'] === AnnouncementStatus::REJECTED->value) {
            $rejectionReason = $validated['rejection_reason'] ?? null;
        }

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
                'rejection_reason' => $rejectionReason,
            ]);

            $currentPackageIds = $announcement->activePackages->pluck('id')->toArray();
            
            if ($request->has('packages')) {
                $submittedPackageIds = $request->packages;
                
                foreach ($submittedPackageIds as $packageId) {
                     $alreadyActive = $announcement->activePackages()->where('package_id', $packageId)->exists();
                     
                     if (!$alreadyActive) {
                        $package = Package::find($packageId);
                        if ($package) {
                            AnnouncementPackage::create([
                                'announcement_id' => $announcement->id,
                                'package_id' => $package->id,
                                'starts_at' => now(),
                                'ends_at' => now()->addDays($package->duration_days),
                            ]);
                        }
                     }
                }

                foreach ($announcement->activePackages as $activePkg) {
                    if (!in_array($activePkg->id, $submittedPackageIds)) {
                         $announcement->announcementPackages()
                             ->where('package_id', $activePkg->id)
                             ->whereNull('deleted_at')
                             ->delete();
                    }
                }
            } else {
                 $announcement->announcementPackages()->delete();
            }

            if ($request->has('images')) {
                $submittedImages = $request->images;
                
                $currentImages = $announcement->images;
                foreach ($currentImages as $image) {
                    $basename = basename($image->path);
                    if (!in_array($basename, $submittedImages)) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }
                }

                foreach ($submittedImages as $index => $imageName) {
                    if (Storage::disk('public')->exists('tmp/' . $imageName)) {
                        $newPath = 'announcements/' . $announcement->id . '/' . $imageName;
                        Storage::disk('public')->move('tmp/' . $imageName, $newPath);
                        
                        AnnouncementImage::create([
                            'announcement_id' => $announcement->id,
                            'path' => $newPath,
                            'is_main' => $index === 0,
                            'order' => $index
                        ]);
                    } else {
                        $img = AnnouncementImage::where('announcement_id', $announcement->id)
                                ->where('path', 'like', '%' . $imageName)
                                ->first();
                                
                        if ($img) {
                            $img->update([
                                'is_main' => $index === 0,
                                'order' => $index
                            ]);
                        }
                    }
                }
            } else {
                foreach ($announcement->images as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }

            notify()->success(t_db('general', 'announcement_updated_successfully'));
            return redirect()->route('announcements.index');

        } catch (\Exception $e) {
            Log::error('Announcement update failed: ' . $e->getMessage());
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
