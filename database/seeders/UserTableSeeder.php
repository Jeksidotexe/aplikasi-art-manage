<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            [
                'nama' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'),
                'no_telepon' => '1223334444',
                'tanggal_daftar' => '2025-05-19',
                'foto' => '/images/default-profile.jpg',
                'role' => 'admin',
                'status' => 'active'
            ],
            [
                'nim' => '1234567890',
                'nama' => 'Anggota 1',
                'email' => 'anggota1@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('anggota123'),
                'no_telepon' => '55555666666',
                'tanggal_daftar' => '2025-05-19',
                'foto' => '/images/anggota.jpeg',
                'role' => 'anggota',
                'status' => 'active'
            ]
        );

        array_map(function (array $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }, $users);
    }
}
