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

class Region extends Model
{
    use SoftDeletes, LogsActivity, HasTranslations, HasSlug;
    protected $table = 'regions';
    protected $fillable = [
        'uuid', 'city_uuid' ,'name', 'slug' ,'is_active'
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
            ->useLogName('region')
            ->setDescriptionForEvent(fn(string $event) => "Region {$event}");
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(fn () => $this->getTranslation('name', 'az'))
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_uuid', 'uuid');
    }

}
