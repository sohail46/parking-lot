<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parking;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Parking::max('id') == 120){
            for ($i = 0; $i < 120; $i++) {
                if($i < 24){
                    $is_reserved = 1;
                } else {
                    $is_reserved = 0;
                }
                Parking::insert([
                    'is_occupied' => 0,
                    'is_reserved' => $is_reserved,
                ]);
            }
        }
    }
}
