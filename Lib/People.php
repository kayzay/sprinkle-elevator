<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 0:56
 */

namespace Lib;


class People
{
    private $directions;
    private $floor = null;

    public function __construct($directions, $floor)
    {
        $this->directions = [
            ElevatorPanel::DIRECTIONS_UP => null,
            ElevatorPanel::DIRECTIONS_DOWN => null
        ];

        if (array_key_exists($directions, $this->directions)) {
            $this->directions[$directions] = true;
        }

        $this->floor = $floor;
    }

    public function getDirections()
    {
        return array_search(true, $this->directions);
    }

    public function getFloor()
    {
        return $this->floor;
    }



}