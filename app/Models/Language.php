<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Language extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'languages';
    protected $fillable = [
        'uuid', 'code', 'name', 'image_path' ,'is_active', 'is_default'
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
            ->logOnly(['code', 'name', 'is_active', 'is_default'])
            ->logOnlyDirty()
            ->useLogName('language')
            ->setDescriptionForEvent(fn(string $event) => "Language {$event}");
    }


    public function translations()
    {
        return $this->hasMany(Translation::class, 'locale', 'code');
    }
}
