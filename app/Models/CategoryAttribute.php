<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_required' => 'boolean',
        'is_filterable' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid', 'uuid');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
