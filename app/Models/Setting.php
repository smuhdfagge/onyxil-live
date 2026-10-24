<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function all_cached(): array
    {
        return Cache::rememberForever('site_settings', function () {
            try {
                return Schema::hasTable('settings') ? static::pluck('value', 'key')->all() : [];
            } catch (\Throwable) {
                return [];
            }
        });
    }

    public static function get(string $key, $default = null)
    {
        $value = static::all_cached()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('site_settings');
    }
}
