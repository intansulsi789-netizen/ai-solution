<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin / Admin Website (Admin Utama)
        User::updateOrCreate(
            ['email' => 'admin@aisolution.id'],
            [
                'name'     => 'admin',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN_WEBSITE,
                'jabatan'  => 'Administrator',
            ]
        );

        $this->command->info('Admin Utama seeded successfully:');
        $this->command->info('   Email:    admin@aisolution.id');
        $this->command->info('   Password: password');
    }
}
