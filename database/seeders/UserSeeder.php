<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'      => 'Tony Hart',
                'email'     => 'tonyzoof@gmail.com',
                'api_token' => '123',
            ]
        ];

        foreach ($users as $user) {
            User::factoryCreate($user + [
                    'password' => Hash::make('12345678')
                ]);
        }
    }
}
