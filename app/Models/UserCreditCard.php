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
        'is_default'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
