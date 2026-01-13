<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use SoftDeletes, HasTranslations;

    protected $table = 'packages';

    protected $fillable = [
        'uuid', 'name', 'is_active', 'duration_days'
    ];

    public array $translatable = [
        'name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_package_prices', 'package_uuid', 'category_uuid', 'uuid', 'uuid')
            ->withPivot('price')
            ->withTimestamps();
    }
    
    public function announcementPackages()
    {
        return $this->hasMany(AnnouncementPackage::class);
    }
}
