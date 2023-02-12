<?php

declare(strict_types=1);

namespace App\Services;

use App\DataProviders\Repository\SeatRepository;

final class SeatService
{
    private $seatRepository;
    public function __construct(SeatRepository $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    public function seatOptions(): array
    {
        $result = [];
        $seats = $this->seatRepository->seats();
        foreach ($seats as $seat) {
            $result[$seat['id']] = $seat['name'];
        }
        return $result;
    }
}
