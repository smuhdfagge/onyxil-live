<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasSlugAndMedia;

    protected $table = 'testimonials';

    protected $fillable = ['name', 'role', 'company', 'quote', 'photo', 'sort', 'is_published'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean'
        ];
    }
}
