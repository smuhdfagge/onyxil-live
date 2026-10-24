<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlugAndMedia
{
    public static function bootHasSlugAndMedia(): void
    {
        static::saving(function ($model) {
            if (in_array('slug', $model->getFillable(), true)) {
                $base = Str::slug($model->slug ?: $model->title ?: $model->name);
                $slug = $base ?: Str::random(8);
                $i = 2;
                while (static::where('slug', $slug)->when($model->exists, fn ($q) => $q->whereKeyNot($model->getKey()))->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    public function mediaUrl(?string $field = 'image'): ?string
    {
        $value = $this->{$field};
        if (! $value) {
            return null;
        }

        return Str::startsWith($value, ['http://', 'https://', '/']) ? $value : asset($value);
    }

    public function getRouteKeyName(): string
    {
        return in_array('slug', $this->getFillable(), true) ? 'slug' : 'id';
    }
}
