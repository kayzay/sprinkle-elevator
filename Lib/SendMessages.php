<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 13:27
 */

namespace Lib;


class SendMessages
{
   private static $mass = '';
    public static function add($massage)
    {
        self::$mass .= $massage . "\n\r<br>";
    }

    public static function send()
    {
        echo self::$mass;
    }
}