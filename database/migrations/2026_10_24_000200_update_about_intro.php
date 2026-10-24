<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Replaces the original "Who we are" paragraph with the new wording.
 * Only touches it if it has not been edited in the admin since.
 */
return new class extends Migration
{
    private string $old = 'Onyx Innovations Limited is an ICT company based in Kano. We write software, set up and look after cloud and network infrastructure, and build the data systems organisations use to make decisions. We also build and run our own products, starting with MyBooks.';

    private string $new = 'Onyx Innovations Limited is an information technology company. We design, build and manage software, cloud and network infrastructure, and the data systems organisations rely on to make sound decisions. We also develop and run our own products, including MyBooks, a cloud accounting platform for growing businesses.';

    public function up(): void
    {
        DB::table('settings')->where('key', 'about_intro')->where('value', $this->old)->update(['value' => $this->new, 'updated_at' => now()]);
        Cache::forget('site_settings');
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'about_intro')->where('value', $this->new)->update(['value' => $this->old, 'updated_at' => now()]);
        Cache::forget('site_settings');
    }
};
