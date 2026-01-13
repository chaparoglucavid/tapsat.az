<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnouncementPackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'announcement_id',
        'package_id',
        'starts_at',
        'ends_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
