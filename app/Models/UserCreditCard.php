<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreditCard extends Model
{
    protected $fillable = [
        'user_id',
        'card_holder_name',
        'card_number',
        'expiration_date',
        'cvv',
        'is_default',
        'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'card_number' => 'encrypted',
        'cvv' => 'encrypted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
