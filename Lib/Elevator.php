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
            $floorStop = 0;

    private $speed = 1000;
    
    const HEIGHT = 4;
    

    const DEFAULT_FLOOR = 1;

    public function __construct($route)
    {
        $this->pavedRoute = $route;
    }

    public function run()
    {
        $moveToFloor = [];
        $pavedRoute = $this->pavedRoute;
       if (count($pavedRoute[ElevatorPanel::DIRECTIONS_UP]) && count($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN])) {
           if (!array_key_exists(self::DEFAULT_FLOOR, $pavedRoute[ElevatorPanel::DIRECTIONS_UP])) {
             SendMessages::send( " - Лифт начел движения с первого этажа");
           } else {
               $this->motionUP($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_UP]);
               SendMessages::send("Подбор и вы садка людей по моршрету с низу верх законина");
               $_max =  max(array_keys($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]));
               if ($this->floorStop < $_max) {
                   SendMessages::send("был совершон вазов с {$_max} и начил движение");
                   $this->floorStop = $_max;
               }

               if (isset($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN][$this->floorStop])) {}
               $this->motionDown($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]);
           }
       } elseif (count($pavedRoute[ElevatorPanel::DIRECTIONS_UP])) {
           $this->motionUP($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_UP]);
       } elseif (count($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN])) {
           if (!array_key_exists(self::DEFAULT_FLOOR, $pavedRoute[ElevatorPanel::DIRECTIONS_UP])) {
               SendMessages::send( " - Лифт начел движения с первого этажа");
           }
           $this->floorStop  =  max(array_keys($pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]));
           $this->motionDown($moveToFloor, $pavedRoute[ElevatorPanel::DIRECTIONS_DOWN]);
       } else {
           SendMessages::send('вызовы не обноружены лифт стоит на 1 этаже');
       }
    }

    private function motionUP($moveToFloor, $pavedRoute)
    {
        $flag = false;
        for ($move = self::HEIGHT, $floor = self::DEFAULT_FLOOR; $move <= House::getHeightHouse(); $move++) {
            if ($flag) {
                SendMessages::send(" - Лифт в место назначения и остановился");
            }

            if (($move % self::HEIGHT) === 0) {
                if (isset($pavedRoute[$floor])) {
                    $moveTo = $pavedRoute[$floor];
                    $moveToFloor[$moveTo] = $moveTo;

                    if (in_array($floor, $moveToFloor)) {
                        SendMessages::send(" - Лифт открил двери на {$floor} этаже, вышел затем  зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::send(" - Лифт закрыл дверь и начил движение");

                        unset($moveToFloor[$floor]);
                    } else {

                        SendMessages::send(" - Лифт открил двери на {$floor} этаже, зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::send(" - Лифт закрыл дверь и начил движение");
                    }

                    unset($pavedRoute[$floor]);

                } elseif (in_array($floor, $moveToFloor)) {
                    unset($moveToFloor[$floor]);

                    SendMessages::send(" - Лифт открил двери на {$floor} этаже, вышел человек");
           
                }
                if (empty($moveToFloor) && empty($pavedRoute)) {
                    $this->floorStop = $floor;
                    break;
                }
                $floor++;
            }
        }
    }

    private function motionDown($moveToFloor, $pavedRoute)
    {
        for ($move = House::calculateHeight($this->floorStop), $floor = $this->floorStop; $move > 1; $move--) {

            if (($move % self::HEIGHT) === 0) {
                if (isset($pavedRoute[$floor])) {
                    $moveTo = $pavedRoute[$floor];
                    $moveToFloor[$moveTo] = $moveTo;

                    if (in_array($floor, $moveToFloor)) {
                        SendMessages::send(" - Лифт открил двери на {$floor} этаже, вышел затем  зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::send(" - Лифт закрыл дверь и начил движение");

                        unset($moveToFloor[$floor]);
                    } else {
                        SendMessages::send(" - Лифт открил двери на {$floor} этаже, зашол человек и задал моршрут на {$moveTo} - этаж");
                        SendMessages::send(" - Лифт закрыл дверь и начил движение");
                    }

                    unset($pavedRoute[$floor]);

                } elseif (in_array($floor, $moveToFloor)) {
                        SendMessages::send(" - Лифт открил двери на {$floor} этаже, вышел человек");

                    if ($floor == self::DEFAULT_FLOOR) {
                        SendMessages::send(" - Лифт закрыл дверь.");
                    } else {
                        SendMessages::send(" - Лифт закрыл дверь и начил движение");
                    }
                    unset($moveToFloor[$floor]);

                }
                if (empty($moveToFloor) && empty($pavedRoute)) {
                    break;
                }
                $floor--;
            }

        }
    }
}