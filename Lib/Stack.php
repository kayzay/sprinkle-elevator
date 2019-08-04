<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 0:57
 */

namespace Lib;


class Stack
{
    private $eventRun;

    public function addEventRun($eventRun)
    { 
        $this->eventRun[] = $eventRun;
    }

    public function getEventRun()
    {
        return $this->eventRun;
    }
}