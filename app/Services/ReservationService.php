<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;
use App\Models\Reservation;
use App\Models\ReservationSeat;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    private function findByReservationMonth($month)
    {
        return Reservation::where('user_id', auth()->user()->id)
            ->where('reservation_date', '>', "{$month}")
            ->get();
    }

    public function reservations($month)
    {
        $result = [];
        $reservations = $this->findByReservationMonth($month);
        foreach ($reservations as $reservation) {
            $result[] = [
                'title' => $reservation['code'] === null ? '예약중' : '완료',
                'start' => $reservation['reservation_date'],
                'start_time' => $reservation['start_time'],
                'end_time' => $reservation['end_time'],
                'reserved_seat_name' => $reservation['reserved_seat_name'],
                'code' => $reservation['code'],
                'message' => $reservation['message'],
            ];
        }
        return $result;
    }

    public function update(array $param): bool
    {
        $startDate  = new DateTime($param['start_date']);
        $endDate = new DateTime($param['end_date']);
        $diffDate = $startDate->diff($endDate);

        for($i=0; $i<$diffDate->days; $i++){
            DB::transaction(function () use ($param, $i) {
                $date = date( 'Y-m-d', strtotime( "+{$i} days" , strtotime($param['start_date'])));
                if (date("N", strtotime($date)) < 6) {
                    $reservation = Reservation::updateOrCreate(
                        ['user_id' => auth()->user()->id, 'reservation_date' => $date],
                        ['start_time' => $param['start_time'], 'end_time' => $param['end_time']]
                    );
                    $this->reservationSeatInsert($reservation->id, $param['user_seats']);
                }
            });
        }
        return true;
    }

    private function reservationSeatInsert(int $reservation_id, array $user_seats)
    {
        if (!empty($reservation_id)) {
            ReservationSeat::where('reservation_id', $reservation_id)->delete();
            $fields = [];
            for ($i=0, $count=count($user_seats); $i<$count; $i++) {
                if (!empty($user_seats[$i])) {
                    $fields[] = [
                        'reservation_id' => $reservation_id,
                        'seat_id' => $user_seats[$i],
                    ];
                }
            }
            if (count($fields) > 0) {
                ReservationSeat::insert($fields);
            }
        }
    }

    public function delete(array $param): bool
    {
        $startDate  = new DateTime($param['start_date']);
        $endDate = new DateTime($param['end_date']);
        $diffDate = $startDate->diff($endDate);

        for($i=0; $i<$diffDate->days; $i++){
            DB::transaction(function () use ($param, $i) {
                $date = date( 'Y-m-d', strtotime( "+{$i} days" , strtotime($param['start_date'])));
                $reservations = Reservation::where('user_id', auth()->user()->id)
                    ->where('reservation_date', $date)->get();
                foreach ($reservations as $reservation) {
                    ReservationSeat::where('reservation_id', $reservation->id)->delete();
                    $reservation->delete();
                }
            });
        }
        return true;
    }
}
