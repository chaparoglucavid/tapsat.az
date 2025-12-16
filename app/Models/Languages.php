<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Languages extends Model
{
    use SoftDeletes;
    protected $table = 'languages';
    protected $fillable = [
        'uuid', 'code', 'name', 'is_active', 'is_default'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uid = Str::uuid()->toString();
            }
        });
    }

    public function translations()
    {
        return $this->hasMany(Translations::class, 'locale', 'code');
    }
}
