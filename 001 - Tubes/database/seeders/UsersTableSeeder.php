<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate(); //for cleaning earlier data to avoid duplicate entries
        DB::table('users')->insert([
            'name' => 'the admin user',
            'email' => 'iamadmin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'nama_toko' => 'Purples Store',
            'nama_pemilik' => 'Jefry',
            'is_active' => 1,
            'no_telp' => '0',
            'alamat' => 'Sumatera Utara',
            'is_data' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'the seller user',
            'email' => 'iamaseller@gmail.com',
            'role' => 'seller',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'nama_toko' => 'Purples Store',
            'nama_pemilik' => 'Jefry',
            'is_active' => 1,
            'no_telp' => '0',
            'alamat' => 'Sumatera Utara',
            'is_data' => 1,
        ]);
    }

    
}
