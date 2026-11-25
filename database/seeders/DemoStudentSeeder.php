<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoStudentSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        $totalStudents = 10;

        $password = Hash::make('Student@12345');

        for ($i = 1; $i <= $totalStudents; $i++) {

            $number = str_pad($i, 3, '0', STR_PAD_LEFT);

            DB::table('users')->insertOrIgnore([
                'name' => 'Demo Student ' . $number,
                'email' => 'student' . $number . '@edutrack.example.test',
                'password' => $password,
                'role' => 'student',
                'account_status' => 'approved',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}