<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Parking;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ParkingRepository extends BaseRepository
{
    private $parking;

    public function __construct(Parking $parking)
    {
        $this->parking = $parking;
    }

    public function get_parkings($is_occupied)
    {
        return $this->parking->where('is_occupied', $is_occupied)->get()->toArray();
    }

    public function parking_exists($is_differently_abled, $is_pregnant)
    {
        $query = $this->parking->newQuery();

        if ($is_differently_abled === 1 || $is_pregnant === true) {
            $query->where(function (Builder $query) {
                return $query->where('is_reserved', 1)
                ->orWhere('is_reserved', 0);
            });
        } else {
            $query->where('is_reserved', 0);
        }

        return $query->exists();
    }

    public function get_first_parking($is_differently_abled, $is_pregnant)
    {
        $query = $this->parking->newQuery();
        $query = $query->where('is_occupied', 0);

        if ($is_differently_abled === 1 || $is_pregnant === true) {
            $query->where(function (Builder $query) {
                return $query->where('is_reserved', 1)
                    ->orWhere('is_reserved', 0);
            });
        } else {
            $query->where('is_reserved', 0);
        }
        return $query->first();
    }

    public function update_parking($parking_id, $data)
    {
        return $this->parking->where('id', $parking_id)->update($data);
    }
}
