<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementAttributeValue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function option()
    {
        return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
    }
}
