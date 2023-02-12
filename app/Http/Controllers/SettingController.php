<?php

namespace App\Http\Controllers;

use App\DataProviders\Repository\SeatRepository;
use App\Http\Requests\SettingRequest;
use App\Services\SeatService;
use App\Services\SettingService;
use Illuminate\Http\Request;
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
        dd($request->user()->setting());
        return view('setting.edit', [
            'user' => $request->user(),
            'reservationTimes' => $this->service->reservationTimes(),
            'seatOptions' => $seatService->seatOptions(),
        ]);
    }

    public function update(SettingRequest $request, SettingService $service)
    {
        $service->update($request->all());
    }
}
