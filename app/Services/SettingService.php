<?php

declare(strict_types=1);

namespace App\Services;

use App\DataProviders\Repository\SettingRepository;
use App\DataProviders\Repository\UserSeatRepository;
use Illuminate\Support\Facades\DB;

final class SettingService
{
    private $settingRepository;
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function reservationTimes()
    {
        $result = [];
        for ($i = 6; $i <= 22; $i++) {
            foreach (['00', '30'] as $minute) {
                if ($i === 22 && $minute === '30') continue;
                $time = sprintf('%02d', $i) . ":" . $minute;
                $result[$time] = $time;
            }
        }
        return $result;
    }

    public function update(array $param)
    {
        $userSeatService = new UserSeatService(new UserSeatRepository());
        DB::transaction(function () use ($param, $userSeatService) {
            $param['meeting_seat_reservation'] = isset($param['meeting_seat_reservation']) ? 1 : 0;
            $this->settingRepository->update($param);
            $userSeatService->update($param['user_seats']);
        });
    }
}
