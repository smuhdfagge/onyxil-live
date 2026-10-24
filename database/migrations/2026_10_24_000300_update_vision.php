<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the original vision statement with the new wording.
 * Only touches it if it has not been edited in the admin since.
 */
return new class extends Migration
{
    private string $old = 'A Northern Nigerian technology company that organisations anywhere in Africa trust with work that matters.';

    private string $new = 'To be a technology partner that organisations across Africa and beyond trust with the systems that matter most to them.';

    public function up(): void
    {
        DB::table('settings')->where('key', 'vision')->where('value', $this->old)->update(['value' => $this->new, 'updated_at' => now()]);
        Cache::forget('site_settings');
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'vision')->where('value', $this->new)->update(['value' => $this->old, 'updated_at' => now()]);
        Cache::forget('site_settings');
    }
};
