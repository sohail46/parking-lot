<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\UserParking;

class UserParkingRepository extends BaseRepository
{
    private $user_parking;

    public function __construct(UserParking $user_parking)
    {
        $this->user_parking = $user_parking;
    }

    public function insert_booking($user_id, $parking_id, $license_plate_no){
        return $this->user_parking->create(['user_id' => $user_id, 'parking_id' => $parking_id, 'license_plate_no' => $license_plate_no]);
    }

    public function update_booking($user_parking_id, $data){
        return $this->user_parking->where('id', $user_parking_id)->update($data);
    }

    public function booking_exists($where){
        return $this->user_parking->where($where)->first();
    }
}
