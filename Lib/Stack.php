<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 0:57
 */

namespace Lib;


use Error;

class Stack
{
    private $eventRun, $route;
    
    public function __construct()
    {
        $this->route = [
            ElevatorPanel::DIRECTIONS_DOWN => [],
            ElevatorPanel::DIRECTIONS_UP => []
        ];
    }

    public function addEventRun(Floor $eventRun)
    { 
        try{
            if (Rules::checkFloor($eventRun->getFloor())) {
                    $this->eventRun[] = $eventRun;
            } else {
                SendMessages::send('Этот этаж не входит в констркцию дома');
            }
        } catch (Error $er) {
            SendMessages::send('что то пошло не так на: ' . $er->getFile() . ' ' . $er->getLine());
        }
    }

 
    public function buildingRoute()
    {
        foreach ($this->eventRun as $key => $item) {
            /** @var $item Floor */
            $people = $item->getPeoples();

            if (isset($this->route[$people->getDirections()])) {
                $this->route[$people->getDirections()][$item->getFloor()] = $people->getFloor();
            }

            ksort($this->route[ElevatorPanel::DIRECTIONS_UP]);
            krsort($this->route[ElevatorPanel::DIRECTIONS_DOWN]);
        }
    }

    public function getEventRun()
    {
        return $this->eventRun;
    }

    public function getRoute()
    {
        return $this->route;
    }
}