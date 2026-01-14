<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class IpRule extends Model
{
    use LogsActivity;

    protected $fillable = [
        'ip_address',
        'type',
        'reason',
        'is_active',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ip_address', 'type', 'reason', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('ip_rule')
            ->setDescriptionForEvent(fn(string $event) => "IP rule {$event}");
    }
}

