<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{

    protected $table = 'quote_requests';

    protected $fillable = ['name', 'email', 'phone', 'company', 'service', 'budget', 'timeline', 'details', 'status', 'notes', 'ip'];

    protected function casts(): array
    {
        return [
            
        ];
    }
}
