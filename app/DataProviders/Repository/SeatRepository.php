<?php

declare(strict_types=1);

namespace App\DataProviders\Repository;

use App\Models\Seat;

class SeatRepository
{
    public static function create(array $param)
    {
        return Seat::insert($param);
    }

    public static function truncate()
    {
        return Seat::truncate();
    }

    public static function seats()
    {
        return Seat::orderBy('name', 'asc')->get();
    }
}
