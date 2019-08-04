<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 0:55
 */

namespace Lib;


class Elevator extends House
{
    private $pavedRoute = [],
            $floorStop = 0,
            $saveProcess = [],
            $eventStop = [];
    
    const HEIGHT = 4;
    const DEFAULT_FLOOR = 1;
    const SPEED = 1;

    public function __construct($route)
    {
        $this->pavedRoute = $route;
    }

    public function AddEventStop($afterFloor)
    {
        if (Rules::checkStopElevator($afterFloor, max(array_merge(
                array_keys($this->pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]),
                array_keys($this->pavedRoute[ElevatorPanel::DIRECTIONS_UP])
            )))) {
            $this->eventStop[$afterFloor] = true;
        }
        return $this;
    }
    
    public function removeStop()
    {
     //   dbg($this->pavedRoute);
        SendMessages::add(date("H:i:s") . " - Лифт снова в движении");
        $this->run();

    }

    public function run()
    {
        $moveToFloor = [];
        $pavedRoute = $this->pavedRoute;
       if (count($pavedRoute[ElevatorPanel::DIRECTIONS_UP]) && count($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN])) {
           if (!array_key_exists(self::DEFAULT_FLOOR, $pavedRoute[ElevatorPanel::DIRECTIONS_UP]) && empty($this->saveProcces)) {
             SendMessages::add(date("H:i:s") . " - Лифт начел движения с первого этажа");
           } else {
               $this->motionUP($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_UP]);
               if (!$this->eventStop) {
                   SendMessages::add(date("H:i:s") . " - Подбор и вы садка людей по моршрету с низу верх законина");
                   $_max =  max(array_keys($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]));
                   if ($this->floorStop < $_max) {
                       SendMessages::add(date("H:i:s") . " - Был совершон вазов с {$_max} и начил движение");
                       $this->floorStop = $_max;
                   }
                   $this->motionDown($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]);
               }
           }
       } elseif (count($pavedRoute[ElevatorPanel::DIRECTIONS_UP])) {
           $this->motionUP($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_UP]);
       } elseif (count($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN])) {
           if (!array_key_exists(self::DEFAULT_FLOOR, $pavedRoute[ElevatorPanel::DIRECTIONS_UP]) && empty($this->saveProcces)) {
               SendMessages::add(date("H:i:s") .  " - Лифт начел движения с первого этажа");
           }

           $_max =  max(array_keys($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]));
           if (!empty($this->saveProcces)) {
               if ($this->saveProcces['f'] < $_max) {
                   SendMessages::add(date("H:i:s") . " - был совершон вазов с {$_max} и начил движение");
                   $this->saveProcces['f'] = $_max;
               }
           }


           $this->floorStop  = $_max;
           $this->motionDown($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]);
       } else {
           SendMessages::add(date("H:i:s") . ' - вызовы не обноружены лифт стоит на 1 этаже');
       }
        return $this;
    }

    private function motionUP($moveToFloor, $pavedRoute)
    {
        $step = (!empty($this->saveProcces))
            ?  $this->saveProcces
            : [
                'm' => self::HEIGHT,
                'f' => self::DEFAULT_FLOOR
            ];
        if (!empty($this->saveProcces)) {
            $this->removeEventStop();
        }
        $flag = false;
        for ($move = $step['m'], $floor = $step['f']; $move <= House::getHeightHouse(); $move++) {
            if ($flag) {
                SendMessages::add(date("H:i:s") . " - Лифт в место назначения и остановился");
            }

            if (($move % self::HEIGHT) === 0) {

                if (isset($pavedRoute[$floor])) {
                    $moveTo = $pavedRoute[$floor];
                    $moveToFloor[$moveTo] = $moveTo;

                    if (in_array($floor, $moveToFloor)) {
                        SendMessages::add(date("H:i:s") . " - Лифт открил двери на {$floor} этаже, вышел затем  зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::add(date("H:i:s") . " - Лифт закрыл дверь и начил движение");

                        unset($moveToFloor[$floor]);
                    } else {

                        SendMessages::add(date("H:i:s") . " - Лифт открил двери на {$floor} этаже, зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::add(date("H:i:s") . " - Лифт закрыл дверь и начил движение");
                    }

                    unset($pavedRoute[$floor]);

                } elseif (in_array($floor, $moveToFloor)) {
                    unset($moveToFloor[$floor]);

                    SendMessages::add(date("H:i:s") . " - Лифт открил двери на {$floor} этаже, вышел человек");
           
                }


                if (isset($this->eventStop[$floor]) && ($move % 2) == 0) {
                    $this->saveProcces = [
                        'm' => $move,
                        'f' => $floor
                    ];
                    $this->pavedRoute[ElevatorPanel::DIRECTIONS_UP] = $moveToFloor;

                    SendMessages::add(date("H:i:s") . " - Люди в ливте нажад кнопку стоп  возле - {$floor} этажа");
                    break;
                }


                if (empty($moveToFloor) && empty($pavedRoute)) {
                    $this->floorStop = $floor;
                    break;
                }

                $floor++;
            }

            sleep(self::SPEED);
        }
    }

    private function motionDown($moveToFloor, $pavedRoute)
    {
        $step = (!empty($this->saveProcces))
            ?  $this->saveProcces
            : [
                'm' => $this->floorStop,
                'f' => $this->floorStop
            ];
        if (!empty($this->saveProcces)) {
            $this->removeEventStop();
        }
        for ($move =  House::calculateHeight($step['m']), $floor = $step['f']; $move > 1; $move--) {

            if (isset($this->eventStop[$floor])) {
                $this->saveProcces = [
                    'm' => $step['m'],
                    'f' => $floor,
                ];
                $this->pavedRoute[ElevatorPanel::DIRECTIONS_DOWN] = $moveToFloor;
                SendMessages::add(date("H:i:s") . " - Люди в ливте нажад кнопку стоп");
                break;
            }

            if (($move % self::HEIGHT) === 0) {
                if (isset($pavedRoute[$floor])) {
                    $moveTo = $pavedRoute[$floor];
                    $moveToFloor[$moveTo] = $moveTo;

                    if (in_array($floor, $moveToFloor)) {
                        SendMessages::add(date("H:i:s") . " - Лифт открил двери на {$floor} этаже, вышел затем  зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::add(date("H:i:s") . " - Лифт закрыл дверь и начил движение");

                        unset($moveToFloor[$floor]);
                    } else {
                        SendMessages::add(date("H:i:s") . " - Лифт открил двери на {$floor} этаже, зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::add(date("H:i:s") . " - Лифт закрыл дверь и начил движение");
                    }

                    unset($pavedRoute[$floor]);

                } elseif (in_array($floor, $moveToFloor)) {
                        SendMessages::add(date("H:i:s") . " - Лифт открил двери на {$floor} этаже, вышел человек");

                    if ($floor == self::DEFAULT_FLOOR) {
                        SendMessages::add(date("H:i:s") . " - Лифт закрыл дверь.");
                    } else {
                        SendMessages::add(date("H:i:s") . " - Лифт закрыл дверь и начил движение");
                    }
                    unset($moveToFloor[$floor]);

                }
                if (empty($moveToFloor) && empty($pavedRoute)) {
                    break;
                }
                $floor--;
            }
            sleep(self::SPEED);
        }
    }

    private function removeEventStop ()
    {
        unset($this->saveProcces, $this->eventStop);
    }
}