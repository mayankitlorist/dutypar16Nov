<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'name' => 'Vishnu Sharma',
                'email' => 'vishnu.sharma@gmail.com',
                'uid' => 'vishnusignifier123',
                'status' => 1,
                'password' => bcrypt('111111'),
                'profile_image' => '',
                'role_type' => 'admin',
            ]
        ];

        foreach ($admins as $admin) {
            User::create($admin);
        }
    }
}
