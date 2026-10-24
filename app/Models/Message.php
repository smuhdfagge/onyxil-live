<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $table = 'messages';

    protected $fillable = ['name', 'email', 'phone', 'company', 'subject', 'message', 'is_read', 'ip'];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean'
        ];
    }
}
