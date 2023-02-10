<?php

declare(strict_types=1);

namespace App\Services;

final class SettingService
{
    public static function reservationTimes()
    {
        $result = [];
        for ($i=6; $i<=22; $i++) {
            foreach (['00', '30'] as $minute) {
                if ($i === 22 && $minute === '30') continue;
                $time = sprintf('%02d', $i).":".$minute;
                $result[$time] = $time;
            }
        }
        return $result;
    }
}