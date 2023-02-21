<?php

declare(strict_types=1);

namespace App\DataProviders\Repository;

use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class SeatRepository
{
    public static function create(array $param)
    {
        return Seat::insert($param);
    }

    public static function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $result = Seat::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }

    public static function seats()
    {
        return Seat::orderBy('name', 'asc')->get();
    }
}
