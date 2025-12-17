<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Translation extends Model
{
    use SoftDeletes;

    protected $table = 'translations';
    protected $fillable = [
        'uuid', 'key', 'locale', 'group','value'
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

    public function language()
    {
        return $this->belongsTo(Language::class, 'locale', 'code');
    }
}
