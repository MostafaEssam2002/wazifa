<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'), // استخدم كلمة مرور مشفرة
                'email_verified_at' => now(), // تعيين تاريخ التحقق من البريد الإلكتروني
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
