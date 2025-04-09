<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RegistrarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'registrar@example.com'],
            [
                'first_name' => 'Registrar',
                'last_name' => 'User',
                'password' => Hash::make('password123')
            ]
        );

        $user->assignRole('registrar');
    }
} 