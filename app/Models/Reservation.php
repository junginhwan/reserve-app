<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_date',
        'start_time',
        'end_time',
        'reserved_seat_name',
        'code',
        'message',
    ];

    public function reservation_seats()
    {
        return $this->hasMany(ReservationSeat::class);
    }
}
