<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MqvService;
use Illuminate\Console\Command;

class MqvReservationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqv:reservation';
    private $service;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MQV 좌석 예약';

    public function __construct(MqvService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::get();
        foreach ($users as $user) {
            if (!empty($user->setting?->mqv_id) && !empty($user->setting?->mqv_password)) {
                $this->service->reservationHandler($user);
            }
        }
        return Command::SUCCESS;
    }
}
