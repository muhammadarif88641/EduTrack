<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin account
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@edutrack.test'],
            [
                'name' => 'EduTrack Admin',
                'role' => 'admin',
                'account_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin@12345'),
                'updated_at' => now(),
            ]
        );

        // Create Lecturer account
        DB::table('users')->updateOrInsert(
            ['email' => 'lecturer@edutrack.test'],
            [
                'name' => 'EduTrack Lecturer',
                'role' => 'lecturer',
                'account_status' => 'approved',
                'email_verified_at' => now(),
                'password' => Hash::make('Lecturer@12345'),
                'updated_at' => now(),
            ]
        );
    }
}