<?php

namespace Lib;

/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 03.08.2019
 * Time: 23:21
 */
class House
{
    const QUANTITY_FLOOR = 4;

    private static $height = 0;

    public static function getHeightHouse()
    {
        return (self::$height != 0) ? self::$height : self::QUANTITY_FLOOR * Elevator::HEIGHT;
    }
    
    public static function calculateHeight($step)
    {
        return $step * Elevator::HEIGHT;
    }

}