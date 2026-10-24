<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ContentSeeder::class);

        $email = env('ADMIN_EMAIL', 'admin@onyxil.com');
        if (! User::where('email', $email)->exists()) {
            $password = env('ADMIN_PASSWORD') ?: Str::password(16, symbols: false);
            User::create([
                'name' => env('ADMIN_NAME', 'Site Admin'),
                'email' => $email,
                'password' => $password,
                'is_admin' => true,
            ]);
            $this->command?->getOutput()->writeln('');
            $this->command?->info('Admin account created. Sign in at /admin/login');
            $this->command?->line('  Email:    '.$email);
            if (! env('ADMIN_PASSWORD')) {
                $this->command?->line('  Password: '.$password.'   (copy this now, it is not shown again)');
            }
        }
    }
}
