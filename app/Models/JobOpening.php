<?php

namespace App\Models;

use App\Models\Concerns\HasSlugAndMedia;
use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    use HasSlugAndMedia;

    protected $table = 'job_openings';

    protected $fillable = ['title', 'slug', 'type', 'location', 'summary', 'body', 'closes_at', 'sort', 'is_published'];

    protected function casts(): array
    {
        return [
            'closes_at' => 'date',
            'is_published' => 'boolean'
        ];
    }

    public function scopeOpen($query)
    {
        return $query->where('is_published', true)->where(fn ($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>=', today()));
    }
}
