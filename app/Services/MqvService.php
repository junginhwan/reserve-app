<?php

declare(strict_types=1);

namespace App\Services;

use xj\snoopy\Snoopy;

class MqvService
{
    private $snoopy;
    private $mqv_url;
    private $mqv_id;
    private $mqv_password;
    private $floorId = 19;

    public function __construct(Snoopy $snoopy)
    {
        $this->snoopy = $snoopy;
        $this->mqv_url = env('MQV_URL');
        $this->mqv_id = env('MQV_ID');
        $this->mqv_password = env('MQV_PASSWORD');

        $this->login();
    }

    private function login(): void
    {
        $this->snoopy->submit("{$this->mqv_url}/api/login", array(
            'username' => $this->mqv_id,
            'password' => $this->mqv_password,
        ));
        $this->snoopy->setcookies();
    }

    private function floors(): array
    {
        $response = $this->snoopy->fetch("{$this->mqv_url}/api/map/floors/{$this->floorId}");
        return json_decode($response->results, true);
    }

    public function seats(): array
    {
        $result = [];
        $floors = $this->floors();
        if (isset($floors['seats'])) {
            foreach ($floors['seats'] as $seat) {
                if (empty($seat['userName'])) {
                    $result[] = [
                        'id' => $seat['seatId'],
                        'name' => $seat['seatName'],
                    ];
                }
            }
        }
        return $result;
    }

    public function snoopy()
    {
        return $this->snoopy;
    }
}
