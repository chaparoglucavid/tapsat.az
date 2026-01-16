<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCreditCard;
use Illuminate\Http\Request;

class UserCreditCardsController extends Controller
{
    // ...

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,uuid', // Validate UUID instead of ID
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'expiration_date' => 'required|string|size:5',
            'cvv' => 'nullable|string|min:3|max:4',
            'is_default' => 'sometimes|boolean',
        ]);

        $user = User::where('uuid', $validated['user_id'])->firstOrFail();
        
        $data = $validated;
        $data['user_id'] = $user->id; // Convert back to ID for storage

        if ($request->has('is_default') && $request->is_default) {
            UserCreditCard::where('user_id', $user->id)->update(['is_default' => false]);
        }

        UserCreditCard::create($data);

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
