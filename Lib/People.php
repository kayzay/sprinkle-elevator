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
    private static $directions;
    private $floor = null;

    const DIRECTIONS_UP = 'up';
    const DIRECTIONS_DOWN = 'down';



    public function __construct($directions, $floor)
    {
        self::$directions = [
            self::DIRECTIONS_UP => null,
            self::DIRECTIONS_DOWN => null
        ];

        if (isset(self::$directions[$directions]))
            self::$directions[$directions] = true;

        $this->floor = $floor;
    }

    public static function getDirections()
    {
        return array_search(true, self::$directions);
    }

    public function getFloor()
    {
        return $this->floor;
    }



}