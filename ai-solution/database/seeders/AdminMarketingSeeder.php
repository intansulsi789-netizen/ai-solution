<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminMarketingSeeder extends Seeder
{
    /**
     * Seed an Admin Marketing/SEO user for testing.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'marketing@aisolution.com'],
            [
                'name'     => 'Admin Marketing',
                'password' => Hash::make('password123'),
                'role'     => User::ROLE_ADMIN_MARKETING,
                'jabatan'  => 'Marketing & SEO',
            ]
        );

        $this->command->info('Admin Marketing/SEO user created:');
        $this->command->info('  Email:    marketing@aisolution.com');
        $this->command->info('  Password: password123');
    }
}
