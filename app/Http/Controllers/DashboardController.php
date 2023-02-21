<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use App\Services\SeatService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function index(Request $request, SeatService $seatService, SettingService $settingService): View
    {
        $reservationService = new ReservationService();
        $seatOptions = $seatService->seatOptions();
        return view('dashboard', [
            'user' => $request->user(),
            'reservationTimes' => $settingService->reservationTimes(),
            'seatOptions' => $seatOptions,
            'reservations' => $reservationService->reservations(date('Y-m-d')),
        ]);
    }
}
