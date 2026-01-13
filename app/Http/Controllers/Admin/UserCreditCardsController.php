<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCreditCard;
use Illuminate\Http\Request;

class UserCreditCardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Not used as standalone for now
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16', // Simple validation
            'expiration_date' => 'required|string|size:5', // MM/YY
            'cvv' => 'nullable|string|min:3|max:4',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($request->has('is_default') && $request->is_default) {
            UserCreditCard::where('user_id', $request->user_id)->update(['is_default' => false]);
        }

        UserCreditCard::create($validated);

        notify()->success(t_db('general', 'credit_card_added_successfully'));
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $card = UserCreditCard::findOrFail($id);
        $card->delete();
        
        notify()->success(t_db('general', 'credit_card_deleted_successfully'));
        return back();
    }
}
