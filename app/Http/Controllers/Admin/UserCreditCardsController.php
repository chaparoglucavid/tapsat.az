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
        // Pre-process card number to remove spaces
        if ($request->has('card_number')) {
            $request->merge([
                'card_number' => str_replace(' ', '', $request->input('card_number'))
            ]);
        }

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
        $card = UserCreditCard::findOrFail($id);
        
        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $card->update(['is_active' => $validated['is_active']]);

        $message = $validated['is_active'] 
            ? t_db('general', 'credit_card_activated_successfully') 
            : t_db('general', 'credit_card_deactivated_successfully');

        notify()->success($message);
        return back();
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
