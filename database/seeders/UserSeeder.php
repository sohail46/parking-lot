<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::max('id') == 25) {
            for($i = 0; $i < 25; $i++){
                User::insert([
                    'name' => Str::random(10),
                    'email' => Str::random(10) . '@gmail.com',
                    'gender' => Arr::random(['male', 'female']),
                    'is_differently_abled' => Arr::random([1, 0]),
                ]);
            }
        }
    }
}
