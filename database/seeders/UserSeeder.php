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
        // 1. Super Admin / Admin Website
        User::updateOrCreate(
            ['email' => 'admin@aisolution.id'],
            [
                'name'     => 'admin',
                'password' => Hash::make('password'),
                'role'     => User::ROLE_ADMIN_WEBSITE,
                'jabatan'  => 'Administrator',
            ]
        );

        // 2. Admin Marketing / SEO
        User::updateOrCreate(
            ['email' => 'marketing@aisolution.com'],
            [
                'name'     => 'Admin Marketing',
                'password' => Hash::make('password123'),
                'role'     => User::ROLE_ADMIN_MARKETING,
                'jabatan'  => 'Marketing & SEO',
            ]
        );

        $this->command->info('Users seeded successfully:');
        $this->command->info('1. Admin Website:');
        $this->command->info('   Email:    admin@aisolution.id');
        $this->command->info('   Password: password');
        $this->command->info('2. Admin Marketing:');
        $this->command->info('   Email:    marketing@aisolution.com');
        $this->command->info('   Password: password123');
    }
}
