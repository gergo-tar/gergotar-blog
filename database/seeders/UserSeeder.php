<?php

namespace Database\Seeders;

use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'Admin';
        $email = 'admin@' . config('mail.filament_user_mail_domain');

        if (User::where('email', $email)->doesntExist()) {
            User::factory()->create([
                'name' => $name,
                'email' => $email,
            ]);
        }
    }
}
