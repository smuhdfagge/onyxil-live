<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasSlugAndMedia;

    protected $table = 'posts';

    protected $fillable = ['title', 'slug', 'category', 'excerpt', 'body', 'image', 'author', 'published_at', 'is_published', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean'
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if ($post->is_published && ! $post->published_at) {
                $post->published_at = now();
            }
        });
    }

    public function scopeLive($query)
    {
        return $query->where('is_published', true)->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 220));
    }
}
