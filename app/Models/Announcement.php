<?php

namespace App\Models;

use App\Enums\AnnouncementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_new' => 'boolean',
        'has_delivery' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => AnnouncementStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->hasMany(AnnouncementImage::class)->orderBy('order');
    }
    
    public function mainImage()
    {
        return $this->hasOne(AnnouncementImage::class)->where('is_main', true);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activePackages()
    {
        return $this->belongsToMany(Package::class, 'announcement_packages')
                    ->withPivot('starts_at', 'ends_at')
                    ->wherePivot('ends_at', '>', now())
                    ->wherePivot('starts_at', '<=', now())
                    ->whereNull('announcement_packages.deleted_at');
    }

    public function announcementPackages()
    {
        return $this->hasMany(AnnouncementPackage::class);
    }

    public function complaints()
    {
        return $this->hasMany(AnnouncementComplaint::class);
    }
}
