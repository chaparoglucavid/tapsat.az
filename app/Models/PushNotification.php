<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'message',
        'target_type',
        'target_value',
        'deep_link',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'target_value' => 'array',
        'sent_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getTargetLabelAttribute()
    {
        switch ($this->target_type) {
            case 'all':
                return 'All Users';
            case 'users':
                $count = is_array($this->target_value) ? count($this->target_value) : 0;
                return "Selected Users ($count)";
            case 'category':
                // Ideally fetch category name, but for listing just showing ID or simple text is enough to start
                return 'Category'; 
            default:
                return ucfirst($this->target_type);
        }
    }
}
