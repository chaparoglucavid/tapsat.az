<?php

namespace App\Enums;

enum AnnouncementStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case INACTIVE = 'inactive';
    case EXPIRED = 'expired';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::INACTIVE => 'Inactive',
            self::EXPIRED => 'Expired',
            self::REJECTED => 'Rejected',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::ACCEPTED => 'success',
            self::INACTIVE => 'secondary',
            self::EXPIRED => 'dark',
            self::REJECTED => 'danger',
        };
    }
}
