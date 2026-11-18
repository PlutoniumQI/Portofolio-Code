<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'email',
    'role',
    'company',
    'category',
    'rating',
    'quote',
    'locale',
    'status',
    'ip_address',
    'user_agent',
    'reviewed_at',
])]
class TestimonialSubmission extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending review',
            self::STATUS_APPROVED => 'Published',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return Testimonial::categories();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }
}
