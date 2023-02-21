<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Services\SeatService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SettingController extends Controller
{
    private $service;
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, SeatService $seatService): View
    {
        $seatOptions = $seatService->seatOptions();
        return view('setting.edit', [
            'user' => $request->user(),
            'reservationTimes' => $this->service->reservationTimes(),
            'seatOptions' => $seatOptions,
            'user_seats' => $request->user()->user_seats()->orderBy('idx')->get(),
        ]);
    }

    public function update(SettingRequest $request, SettingService $service)
    {
        $service->update($request->all());
        return Redirect::route('setting.edit')->with('status', 'setting-updated');
    }
}
