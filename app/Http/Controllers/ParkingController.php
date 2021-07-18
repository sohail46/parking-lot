<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ParkingRepository;
use App\Exceptions\ApiException;

class ParkingController extends Controller
{
    private $parking_repository;

    public function __construct(ParkingRepository $parking_repository)
    {
        $this->parking_repository = $parking_repository;
    }

    public function available_parkings()
    {
        $parkings = $this->parking_repository->get_parkings(0);

        if (empty($parkings)) {
            throw new ApiException('client', 404, '', 'No Available Parkings Found!');
        }
        return $this->jsonResponse($parkings, 'Available Parkings Found!');
    }

    public function occupied_parkings()
    {
        $parkings = $this->parking_repository->get_parkings(1);

        if (empty($parkings)) {
            throw new ApiException('client', 404, '', 'No Occupied Parkings Found!');
        }
        return $this->jsonResponse($parkings, 'Occupied Parkings Found!');
    }
}
