<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\MqvService;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class MqvReservationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqv:reservation';
    private $service;
    private $logger;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MQV 좌석 예약';

    public function __construct(MqvService $service, LoggerInterface $logger)
    {
        parent::__construct();
        $this->service = $service;
        $this->logger = $logger;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->logger->info(__METHOD__. " START");
        $users = User::get();
        foreach ($users as $user) {
            if (!empty($user->setting?->mqv_id) && !empty($user->setting?->mqv_password)) {
                $this->logger->info(__METHOD__. " name : {$user->name}, mqv_id : {$user->setting?->mqv_id} mqv_pwd : {$user->setting?->mqv_password}");
                $this->service->reservationHandler($user, $this->logger);
            }
        }
        $this->logger->info(__METHOD__. " END");
        return Command::SUCCESS;
    }
}
