<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 05.08.2019
 * Time: 0:00
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

$elevator = new Elevator($stack->getRoute());
$elevator->AddEventStop(3);
$elevator->run();
sleep(3);
$elevator->removeStop();

\Lib\SendMessages::send();