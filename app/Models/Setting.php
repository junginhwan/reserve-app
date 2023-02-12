<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mqv_id',
        'mqv_password',
        'start_time',
        'end_time',
        'meeting_seat_reservation',
    ];

    protected $hidden = [
        'mqv_password',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
