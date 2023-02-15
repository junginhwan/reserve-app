<?php

declare(strict_types=1);

namespace App\Services;

use App\DataProviders\Repository\UserSeatRepository;
use Illuminate\Support\Facades\Auth;

final class UserSeatService
{
    private $userSeatRepository;
    public function __construct(UserSeatRepository $userSeatRepository)
    {
        $this->userSeatRepository = $userSeatRepository;
    }

    public function update(array $user_seats)
    {
        $this->userSeatRepository->delete();
        $this->inserts(array_unique(array_filter($user_seats)));
    }

    private function inserts(array $user_seats)
    {
        $inserts = [];
        foreach ($user_seats as $user_seat) {
            $inserts[] = [
                'user_id' => Auth::id(),
                'seat_id' => $user_seat,
            ];
        }
        $this->userSeatRepository->create($inserts);
    }
}