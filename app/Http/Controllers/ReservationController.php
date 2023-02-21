<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationUpdateRequest;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private $service;
    public function __construct(ReservationService $service)
    {
        $this->service = $service;
    }

    public function update(ReservationUpdateRequest $request)
    {
        $this->service->update($request->all());
        return Redirect::route('dashboard')->with('status', 'reservation-updated');
    }

    public function delete(Request $request)
    {
        $this->service->delete($request->all());
        return Redirect::route('dashboard')->with('status', 'reservation-updated');
    }
}
