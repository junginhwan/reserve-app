<?php

namespace App\Console\Commands;

use App\DataProviders\Repository\SeatRepository;
use App\Services\MqvService;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

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
    private $logger;

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
        $this->service->login();
        $seats = $this->service->seats();
        if (count($seats) > 0) {
            SeatRepository::truncate();
            SeatRepository::create($seats);
        }
        $this->logger->info(__METHOD__. " END");
        return Command::SUCCESS;
    }
}
