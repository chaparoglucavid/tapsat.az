<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementComplaint extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function subject()
    {
        return $this->belongsTo(ComplaintSubject::class, 'complaint_subject_id');
    }
}

