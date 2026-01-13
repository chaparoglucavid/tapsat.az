<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'announcement_id',
        'package_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'description',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
