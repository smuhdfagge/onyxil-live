<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasSlugAndMedia;

    protected $table = 'clients';

    protected $fillable = ['name', 'logo', 'url', 'sort', 'is_published'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean'
        ];
    }
}
