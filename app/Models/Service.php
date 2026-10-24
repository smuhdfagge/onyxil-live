<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSlugAndMedia;

    protected $table = 'services';

    protected $fillable = ['title', 'slug', 'icon', 'summary', 'body', 'features', 'deliverables', 'image', 'sort', 'is_featured', 'is_published', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'deliverables' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean'
        ];
    }
}
