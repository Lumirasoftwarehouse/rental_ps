<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Menu;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => Carbon::create(2024, 10, 10, 12, 0, 0), // Format: (year, month, day, hour, minute, second)
            'password' => Hash::make('12345'),
            'level' => '0',
            'created_at' => Carbon::create(2024, 10, 10, 12, 0, 0), // Format: (year, month, day, hour, minute, second)
            'updated_at' => Carbon::create(2024, 10, 10, 12, 0, 0) // Format: (year, month, day, hour, minute, second)
        ]);
        // User
        User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => Carbon::create(2024, 10, 10, 12, 0, 0), // Format: (year, month, day, hour, minute, second)
            'password' => Hash::make('12345'),
            'level' => '1',
            'created_at' => Carbon::create(2024, 10, 10, 12, 0, 0), // Format: (year, month, day, hour, minute, second)
            'updated_at' => Carbon::create(2024, 10, 10, 12, 0, 0) // Format: (year, month, day, hour, minute, second)
        ]);

        Menu::create([
            'name' => 'PS 5 A',
            'jenis' => 'PS 5',
            'image' => '-'
        ]);
        Menu::create([
            'name' => 'PS 5 B',
            'jenis' => 'PS 5',
            'image' => '-'
        ]);
        Menu::create([
            'name' => 'PS 4 A',
            'jenis' => 'PS 4',
            'image' => '-'
        ]);
        Menu::create([
            'name' => 'PS 4 B',
            'jenis' => 'PS 4',
            'image' => '-'
        ]);

        // Pemesanan::create([
        //     'tanggal_sewa' => '2025/03/16',
        //     'durasi' => '2',
        //     'menu_id' => '1',
        //     'user_id' => '1'
        // ]);

    }
}
