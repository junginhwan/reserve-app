<?php

declare(strict_types=1);

namespace App\Services;

use xj\snoopy\Snoopy;
use App\Models\Reservation;

class MqvService
{
    private $snoopy;
    private $mqv_url;
    private $mqv_id;
    private $mqv_password;
    private $floorId = 19;
    private $meetingRoomsReservations;
    private $reservation_dates = [];

    public function __construct(Snoopy $snoopy)
    {
        $this->snoopy = $snoopy;
        $this->mqv_url = env('MQV_URL');
        $this->mqv_id = env('MQV_ID');
        $this->mqv_password = env('MQV_PASSWORD');
    }
    
    private function setMqvId($mqv_id): void
    {
        $this->mqv_id = $mqv_id;
    }

    private function setMqvPassword($mqv_password): void
    {
        $this->mqv_password = $mqv_password;
    }

    private function setMeetingRoomsReservations($meetingRoomsReservations): void
    {
        $this->meetingRoomsReservations = $meetingRoomsReservations;
    }

    public function login(): void
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

    private function meetingRoomsReservation()
    {
        $this->snoopy->fetch("{$this->mqv_url}/api/v1/meeting-rooms/current-reservations");
        return json_decode($this->snoopy->results, true);
    }

    public function reservationHandler($user, $logger)
    {
        if (empty($this->reservation_dates[$user->id])) {
            $this->reservation_dates[$user->id] = [];
        }

        if ((int) $user->setting?->meeting_seat_reservation === 1) {
            $this->setMqvId($user->setting?->mqv_id);
            $this->setMqvPassword($user->setting?->mqv_password);
            $this->login();
            $this->autoReservation($user, $logger);
        }

        $this->reservation($user, $logger);
    }

    private function reservation($user, $logger)
    {
        $datetime = new \DateTime();
        $datetime->add(new \DateInterval('P1D'));

        $reservation = Reservation::where('user_id', $user->id)
            ->where('reservation_date', $datetime->format('Y-m-d'))
            ->whereNull('code')
            ->first();

        if (!empty($reservation->id)) {
            $reservation_date = $reservation->reservation_date;
            if (strtotime($datetime->format('Y-m-d')) === strtotime($reservation_date) && !in_array($reservation_date, $this->reservation_dates[$user->id])) {
                if ((int) $user->setting?->meeting_seat_reservation !== 1) {
                    $this->setMqvId($user->setting?->mqv_id);
                    $this->setMqvPassword($user->setting?->mqv_password);
                    $this->login();
                }

                $exec = $this->reservationExec($datetime, $user, $reservation->reservation_seats, $reservation->start_time, $reservation->end_time);
                $logger->info(__METHOD__. "{$user->name} message : {$exec['message']}");

                $reservation->code = (!empty($exec['code'])) ? $exec['code'] : 'SUCCESS';
                $reservation->message = $exec['message'];
                $reservation->save();
                // mail($user->email, $message, $message);
            }
        }
    }

    private function autoReservation($user, $logger)
    {

        $datetime = new \DateTime();
        $datetime->add(new \DateInterval('P1D'));

        if ((int) $user->setting?->meeting_seat_reservation === 1) {
            $reservations = $this->meetingRoomsReservation();
            $this->setMeetingRoomsReservations($reservations);
            if ($this->isMeetingRoomsReservations()) {
                $logger->info(__METHOD__. "{$user->name} 미팅 일정 확인");
                foreach ($reservations['meetingRoomReservationList'] as $reservation) {
                    $reservation_date = $reservation['startAt'][0]."-".sprintf('%02d',$reservation['startAt'][1])."-".$reservation['startAt'][2];
                    if (strtotime($datetime->format('Y-m-d')) === strtotime($reservation_date) && !in_array($reservation_date, $this->reservation_dates[$user->id])) {
                        $exec = $this->reservationExec($datetime, $user, $user->user_seats, $user->setting?->start_time, $user->setting?->end_time);
                        $logger->info(__METHOD__. "{$user->name} message : {$exec['message']}");
                        // mail($user->email, $message, $message);
                    }
                }
            } else {
                $logger->info(__METHOD__. "{$user->name} 등록된 미팅 일정이 없습니다.");
            }
        }
    }

    private function reservationExec($datetime, $user, $seats, $start_time, $end_time)
    {
        list($start_date, $end_date) = $this->reserveDate($datetime, $start_time, $end_time);
        $message = "";
        $code = "";
        $reservation_date = $datetime->format('Y-m-d');
        foreach ($seats as $seat) {
            $result = $this->seatReservation($start_date, $end_date, $seat->seat_id);
            if (empty($result['code'])) {
                $this->reservation_dates[$user->id][] = $reservation_date;
                $message = "성공 : MQV {$reservation_date} 자리 예약에 성공하였습니다.";
                break;
            } else {
                $message .= "실패 : {$result['code']} {$result['message']} <br/>";
                $code = $result['code'];
                if ($result['code'] === 'CONFLICT') { // 이미 예약
                    break;
                }
            }
        }
        return [
            'code' => $code,
            'message' => $message,
        ];
    }

    private function seatReservation($start_date, $end_date, $seat_id)
    {
        $this->snoopy->submit("{$this->mqv_url}/api/seats/{$seat_id}/reservations", array(
            'startAt' => $start_date,
            'endAt' => $end_date,
        ));
        return json_decode($this->snoopy->results, true);
    }

    private function reserveDate($datetime, $start_time, $end_time)
    {
        $start_time = explode(":", $start_time);
        $end_time = explode(":", $end_time);
        $datetime->setTime((int) $start_time[0], (int) $start_time[1], 0);
        $start_date = strtotime($datetime->format('Y-m-d H:i:s'));
        $datetime->setTime((int) $end_time[0], (int) $end_time[1], 0);
        $end_date = strtotime($datetime->format('Y-m-d H:i:s'));
        return array(
            $start_date,
            $end_date
        );
    }

    private function isMeetingRoomsReservations(): bool
    {
        $result = false;
        $reservations = $this->meetingRoomsReservations;
        if (is_array($reservations['meetingRoomReservationList'])) {
            foreach ($reservations['meetingRoomReservationList'] as $reservation) {
                if (is_array($reservation['attendeeList'])) {
                    foreach ($reservation['attendeeList'] as $attendeeList) {
                        if ($attendeeList['attendeeLoginId'] === $this->mqv_id) {
                            $result = true;
                        }
                    }
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
