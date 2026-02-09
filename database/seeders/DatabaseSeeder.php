<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


public function run(): void
{
    // Akun Mentor
    User::create([
        'name' => 'Pak Mentor',
        'email' => 'mentor@magang.com',
        'password' => bcrypt('password'),
        'role' => 'mentor'
    ]);

    // Akun Peserta Magang
    User::create([
        'name' => 'Budi Magang',
        'email' => 'budi@magang.com',
        'password' => bcrypt('password'),
        'role' => 'intern'
    ]);
}
}
