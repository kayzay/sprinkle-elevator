<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 0:57
 */

namespace Lib;


class Rules
{
    const QUANTITY_PEOPLES = 4;

    public static function checkFloor(int $numberFloor) :bool 
    {
        return ($numberFloor >= 1 && $numberFloor <= House::QUANTITY_FLOOR);
    }
    
}