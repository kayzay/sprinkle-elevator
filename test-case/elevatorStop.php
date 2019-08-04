<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 23:55
 */

use Lib\Elevator;
use Lib\ElevatorPanel;
use Lib\Floor;
use Lib\People;
use Lib\Stack;

require_once(dirname(__DIR__)) . "/vendor/autoload.php";


$stack = new Stack();

$stack->addEventRun(
    (new Floor(1))
        ->addPeople(new People(ElevatorPanel::DIRECTIONS_UP, 4))
);
$stack->addEventRun(
    (new Floor(3))
        ->addPeople(new People(ElevatorPanel::DIRECTIONS_DOWN, 2))
);
$stack->addEventRun(
    (new Floor(4))
        ->addPeople(new People(ElevatorPanel::DIRECTIONS_DOWN, 1))
);
$stack->buildingRoute();

(new Elevator($stack->getRoute()))
    ->AddEventStop(3)
    ->run();

\Lib\SendMessages::send();