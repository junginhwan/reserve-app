<?php

declare(strict_types=1);

namespace App\DataProviders\Repository;

use App\Models\UserSeat;
use Illuminate\Support\Facades\Auth;

class UserSeatRepository
{
    public static function delete()
    {
        return UserSeat::where('user_id', Auth::id())->delete();
    }

    public static function create(array $param)
    {
        return UserSeat::insert($param);
    }
}
