<?php

namespace App\Http\Controllers\Admin\Stores;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use App\Enums\AnnouncementStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with(['user', 'category']);

        if ($request->has('status') && in_array($request->status, ['pending', 'confirmed', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $stores = $query->latest()->paginate(10);

        return view('admin-dashboard.stores.index', compact('stores'));
    }

    public function create()
    {
        $categories = Category::where('parent_uuid', null)->get(); // Assuming we pick main categories or all? Let's pass all active categories for now or how it's usually done. 
        // Better: Category::isActive()->get(); but categories are nested. 
        // For simplicity, I'll just get all active categories.
        $categories = Category::all(); 
        $users = User::where('type', 'user')->get();

        return view('admin-dashboard.stores.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'working_days' => 'nullable|string', // Assuming multiselect returns comma separated or handled as array
            'working_hours' => 'required|string',
            'logo' => 'nullable|string',
            'banner_image' => 'nullable|string',
            
            // User validation
            'user_selection_type' => 'required|in:existing,new',
            'user_id' => 'required_if:user_selection_type,existing|nullable|exists:users,id',
            'new_user_name' => 'required_if:user_selection_type,new|nullable|string|max:255',
            'new_user_email' => 'required_if:user_selection_type,new|nullable|email|unique:users,email',
            'new_user_password' => 'required_if:user_selection_type,new|nullable|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            // 1. Handle User
            if ($request->user_selection_type === 'new') {
                $user = User::create([
                    'name' => $request->new_user_name,
                    'email' => $request->new_user_email,
                    'password' => Hash::make($request->new_user_password),
                    'phone_number' => $request->phone_number, // Use store phone for user as well? Or separate field? Prompt didn't specify user phone. I'll use store phone or leave empty if nullable. User phone is nullable in migration? Let's check User model. User model has phone_number in fillable.
                    'type' => 'user', // Assuming 'user' is the type
                ]);
            } else {
                $user = User::findOrFail($request->user_id);
            }

            // 2. Create Store
            $store = Store::create([
                'store_name' => $request->store_name,
                'category_id' => $request->category_id,
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'working_days' => $request->working_days ? explode(',', $request->working_days) : [], // Assuming comma separated string from frontend
                'working_hours' => $request->working_hours,
                'status' => 'pending',
            ]);

            // 3. Handle Images (Move from tmp)
            if ($request->logo) {
                $this->moveImage($request->logo, $store, 'logo');
            }

            if ($request->banner_image) {
                $this->moveImage($request->banner_image, $store, 'banner_image');
            }

            DB::commit();

            return redirect()->route('stores.index')->with('success', t_db('general', 'store_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($uuid)
    {
        $store = Store::with(['user', 'category'])->where('uuid', $uuid)->firstOrFail();
        return view('admin-dashboard.stores.show', compact('store'));
    }

    public function edit($uuid)
    {
        $store = Store::where('uuid', $uuid)->firstOrFail();
        $categories = Category::all();
        return view('admin-dashboard.stores.edit', compact('store', 'categories'));
    }

    public function update(Request $request, $uuid)
    {
        $store = Store::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'store_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'working_days' => 'nullable|string',
            'working_hours' => 'required|string',
            'logo' => 'nullable|string',
            'banner_image' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $store->update([
                'store_name' => $request->store_name,
                'category_id' => $request->category_id,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'working_days' => $request->working_days ? explode(',', $request->working_days) : [],
                'working_hours' => $request->working_hours,
            ]);

            // Handle Images
            if ($request->logo) {
                // Delete old logo if exists? Usually good practice but optional for now.
                $this->moveImage($request->logo, $store, 'logo');
            }

            if ($request->banner_image) {
                $this->moveImage($request->banner_image, $store, 'banner_image');
            }

            DB::commit();

            return redirect()->route('stores.index')->with('success', t_db('general', 'store_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy($uuid)
    {
        $store = Store::where('uuid', $uuid)->firstOrFail();
        
        // Remove store_owner flag from user if confirmed
        if ($store->status == 'confirmed' && $store->user) {
            $store->user->update(['store_owner' => false]);
        }

        $store->delete();

        return redirect()->route('stores.index')->with('success', t_db('general', 'store_deleted_successfully'));
    }

    public function updateStatus(Request $request, $uuid)
    {
        $store = Store::where('uuid', $uuid)->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:confirmed,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $store->status = $request->status;

            if ($request->status === 'rejected') {
                $store->rejection_reason = $request->rejection_reason;
                // If previously confirmed, remove store_owner?
                if ($store->user->store_owner) {
                    $store->user->update(['store_owner' => false]);
                }

                // Deactivate all active announcements for this store
                $store->announcements()->where('status', AnnouncementStatus::ACTIVE->value)->update([
                    'status' => AnnouncementStatus::INACTIVE->value
                ]);
            } elseif ($request->status === 'confirmed') {
                $store->rejection_reason = null;
                // Update User to be store_owner
                $store->user->update(['store_owner' => true]);
            }

            $store->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => t_db('general', 'status_updated_successfully')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function moveImage($filename, $store, $column)
    {
        if (Storage::disk('public')->exists('tmp/' . $filename)) {
            $newPath = 'stores/' . $store->id . '/' . $filename;
            Storage::disk('public')->move('tmp/' . $filename, $newPath);
            $store->update([$column => $newPath]);
        }
    }
}
