<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasSlugAndMedia;

    protected $table = 'products';

    protected $fillable = ['title', 'slug', 'tagline', 'summary', 'body', 'url', 'cta_label', 'logo', 'image', 'features', 'audiences', 'pricing_note', 'status', 'sort', 'is_featured', 'is_published', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'audiences' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean'
        ];
    }
}
