<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
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

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }
}
