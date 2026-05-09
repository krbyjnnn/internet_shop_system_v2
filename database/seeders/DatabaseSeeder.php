<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name'     =>'administrator',
            'username' =>'admin',
            'password' =>Hash::make('admin123'),
            'role'     =>'admin',
            'balance'  =>0,     
            ]);

        for ($i = 1; $i <= 20; $i++)
            {
                $number = str_pad($i, 2, '0', STR_PAD_LEFT);

                Station::create([
                    'name'          => 'PC ' . $number,
                    'password'      => 'pc' . $number,
                    'is_occupied'   => false,
                    'user_id'       => null 
                ]);
            }
    }
}
