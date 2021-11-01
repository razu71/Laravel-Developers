<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'user_name' => 'admin',
            'password' => Hash::make('123456'),
            'avatar' => null,
            'email' => 'admin@email.com',
            'user_role' => ADMIN,
            'registered_at' => now(),
        ]);
    }
}
