<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{

    protected $table = 'seo_pages';

    protected $fillable = ['page', 'label', 'meta_title', 'meta_description', 'og_image'];

    protected function casts(): array
    {
        return [
            
        ];
    }
}
