<?php

declare(strict_types=1);

namespace App\DataProviders\Repository;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingRepository
{
    public function update(array $param)
    {
        return Setting::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'mqv_id' => $param['mqv_id'],
                'mqv_password' => $param['mqv_password'],
                'start_time' => $param['start_time'],
                'end_time' => $param['end_time'],
                'meeting_seat_reservation' => $param['meeting_seat_reservation'] ?? 0,
            ]
        );
    }
}
