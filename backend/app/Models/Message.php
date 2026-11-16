<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'type',
    'name',
    'email',
    'subject',
    'message',
    'is_read',
    'is_approved',
    'ip_address',
    'user_agent',
])]
class Message extends Model
{
    public const TYPE_CONTACT = 'contact';

    public const TYPE_GUESTBOOK = 'guestbook';

    /**
     * @return array<string, string>
     */
    public static function types(): array
    {
        return [
            self::TYPE_CONTACT => 'Contact message',
            self::TYPE_GUESTBOOK => 'Guestbook entry',
        ];
    }

    public function scopeApprovedGuestbook(Builder $query): Builder
    {
        return $query
            ->where('type', self::TYPE_GUESTBOOK)
            ->where('is_approved', true);
    }

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }
}
