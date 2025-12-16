<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLogins extends Model
{
    protected $table = 'failed_logins';
    protected $fillable = [
        'email',
        'ip_address',
        'attempts',
        'locked_until',
    ];
}
