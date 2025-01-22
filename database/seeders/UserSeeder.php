<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'User 1',
                'email' => 'user1@webtech.id',
                'password' => Hash::make('password1'),
                
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@webtech.id',
                'password' => Hash::make('password2'),
                    
            ],
            [
                'name' => 'User 3',
                'email' => 'user3@worldskills.org',
                'password' => Hash::make('password3'),

            ],
           
        ];
        foreach($users as $user){
            User::create($user);
        }
    }
}
