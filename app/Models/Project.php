<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasSlugAndMedia;

    protected $table = 'projects';

    protected $fillable = ['title', 'slug', 'client', 'industry', 'year', 'summary', 'challenge', 'solution', 'results', 'stack', 'url', 'image', 'sort', 'is_featured', 'is_published', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'stack' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean'
        ];
    }
}
