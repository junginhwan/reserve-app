<?php

namespace App\Console\Commands;

use App\DataProviders\Repository\SeatRepository;
use App\Services\MqvService;
use Illuminate\Console\Command;

class MqvSeatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqv:seat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MQV 좌석 정보를 크롤링한 후 DB에 저장합니다.';

    private $service;

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
        $this->service->login();
        $seats = $this->service->seats();
        if (count($seats) > 0) {
            SeatRepository::truncate();
            SeatRepository::create($seats);
        }
        return Command::SUCCESS;
    }
}
