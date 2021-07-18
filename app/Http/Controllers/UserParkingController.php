<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserParkingRepository;
use App\Repositories\UserRepository;
use App\Repositories\ParkingRepository;
use App\Exceptions\ApiException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserParkingController extends Controller{
    private $user_parking_repository, $user_repository, $parking_repository;

    public function __construct(UserParkingRepository $user_parking_repository, UserRepository $user_repository, ParkingRepository $parking_repository){
        $this->user_parking_repository = $user_parking_repository;
        $this->user_repository = $user_repository;
        $this->parking_repository = $parking_repository;
    }

    public function create_booking(Request $request){
        $data = $request->all();

        $rules = [
            'user_id' => 'required|integer',
            'is_pregnant' => 'required|boolean',
            'license_plate_no' => 'required'
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ApiException('client', 412, '', implode(', ', $validator->errors()->all()));
        }

        $user = $this->user_repository->get_user($data['user_id']);

        if(empty($user)){
            throw new ApiException('client', 404, '', 'User Not Found!');
        }

        if($data['is_pregnant'] && $user['gender'] == 'male'){
            throw new ApiException('client', 400, '', 'Invalid Request!');
        }

        $parking = $this->parking_repository->get_first_parking($user['is_differently_abled'], $data['is_pregnant']);

        if(empty($parking)){
            throw new ApiException('client', 400, '', 'All Parkings Occupied!');
        }

        $is_booking_inserted = $this->user_parking_repository->insert_booking($data['user_id'], $parking['id'], $data['license_plate_no']);

        if($is_booking_inserted){
            $parking_data = [
                'is_occupied' => 1
            ];
            $this->parking_repository->update_parking($parking['id'], $parking_data);
            return $this->jsonResponse($is_booking_inserted, 'Parking Booked Successfully!');
        } 
        throw new ApiException('client', 400, '', 'Someting went wrong, Please try again later!');
    }

    public function user_reached_parking($id){
        $where = ['id' => $id];
        $booking = $this->user_parking_repository->booking_exists($where);
        if($booking['has_reached'] == 1){
            throw new ApiException('client', 400, '', 'Already Reached Parking, Status Cant be Updated!');
        }

        $data = [
            'has_reached' => 1,
            'reached_at' => Carbon::now()
        ];
        $is_updated = $this->user_parking_repository->update_booking($id, $data);
        if($is_updated){
            return $this->jsonResponse('', 'Parking Updated Successfully!');
        }
        throw new ApiException('client', 404, '', 'Invalid Parking or Parking not Found!');
    }

    public function user_exited_parking($id){
        $where = ['id' => $id];
        $booking = $this->user_parking_repository->booking_exists($where);
        if ($booking['has_exited'] == 1) {
            throw new ApiException('client', 400, '', 'Already Exited Parking, Status Cant be Updated!');
        }

        $data = [
            'has_exited' => 1,
            'exited_at' => Carbon::now()
        ];
        $is_updated = $this->user_parking_repository->update_booking($id, $data);

        if($is_updated){
            $parking_data = [
                'is_occupied' => 0
            ];
            $this->parking_repository->update_parking($booking['parking_id'], $parking_data);
            return $this->jsonResponse('', 'Parking Updated Successfully!');
        }
        throw new ApiException('client', 404, '', 'Invalid Parking or Parking not Found!');
    }
}
