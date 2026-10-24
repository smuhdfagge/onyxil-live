<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('onyx:admin {email} {--name=Site Admin} {--password=}', function (string $email) {
    $password = $this->option('password') ?: \Illuminate\Support\Str::password(16, symbols: false);
    $user = \App\Models\User::firstOrNew(['email' => $email]);
    $user->fill(['name' => $user->exists ? $user->name : $this->option('name'), 'password' => $password, 'is_admin' => true])->save();
    $this->info(($user->wasRecentlyCreated ? 'Admin created' : 'Password reset').' for '.$email);
    if (! $this->option('password')) {
        $this->line('Password: '.$password);
    }
})->purpose('Create an admin account or reset an admin password');
