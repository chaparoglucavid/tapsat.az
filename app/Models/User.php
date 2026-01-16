<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Enums\UserType;

use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone_number',
        'password',
        'otp_code',
        'otp_expires_at',
        'failed_login_attempts',
        'locked_until',
        'type',
        'store_owner'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
        'otp_code',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
        ];
    }

    public function creditCards()
    {
        return $this->hasMany(UserCreditCard::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function scopeIsUser($query)
    {
        return $query->where('type', UserType::USER);
    }

    public function scopeIsAdmin($query)
    {
        return $query->where('type', UserType::ADMIN);
    }
}
