<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@portfolio.local')],
            [
                'name' => 'Portfolio Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'is_admin' => true,
            ],
        );

        if (! $admin->is_admin) {
            $admin->forceFill(['is_admin' => true])->save();
        }

        $this->call(PortfolioSeeder::class);
    }
}
