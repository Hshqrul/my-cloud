<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $sid = Str::uuid();
        DB::table('users')->insert([
            [
                'id' => $sid,
                'name' => 'Hashaqirul',
                'username' => 'superadmin',
                'email' => 'hashaqirul@aech.com',
                'email_verified_at' => now(),
                'password' => Hash::make('superadmin'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'user',
                'username' => 'usermycloud',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('user@example.com'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
