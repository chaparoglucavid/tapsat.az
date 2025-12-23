<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use SoftDeletes, LogsActivity, HasTranslations;
    protected $table = 'cities';
    protected $fillable = [
        'uuid', 'name', 'is_active'
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
            ->useLogName('city')
            ->setDescriptionForEvent(fn(string $event) => "City {$event}");
    }

}
