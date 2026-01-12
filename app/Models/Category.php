<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use SoftDeletes, LogsActivity, HasTranslations, HasSlug;
    protected $table = 'categories';
    protected $fillable = [
        'uuid', 'parent_uuid' ,'name', 'slug' ,'is_active'
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

    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->oldAttributes = $model->getAttributes();
        });
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName === 'deleted') {
            $activity->properties = $activity->properties->merge([
                'deleted_attributes' => $this->oldAttributes ?? []
            ]);
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name','status'])
            ->logOnlyDirty()
            ->useLogName('category')
            ->setDescriptionForEvent(fn(string $event) => "Category {$event}");
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn () => $this->getTranslation('name', 'az'))
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_uuid', 'uuid');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_uuid', 'uuid');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'category_package_prices', 'category_uuid', 'package_uuid', 'uuid', 'uuid')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes', 'category_uuid', 'attribute_id', 'uuid', 'id')
            ->withPivot('is_required', 'is_filterable')
            ->withTimestamps();
    }
}
