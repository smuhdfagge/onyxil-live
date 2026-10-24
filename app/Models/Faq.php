<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasSlugAndMedia;

    protected $table = 'faqs';

    protected $fillable = ['question', 'answer', 'group', 'sort', 'is_published'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean'
        ];
    }
}
