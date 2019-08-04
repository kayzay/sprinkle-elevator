<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 03.08.2019
 * Time: 22:55
 */


use Lib\Elevator;
use Lib\ElevatorPanel;
use Lib\Floor;
use Lib\People;
use Lib\Stack;

require_once __DIR__ . "/vendor/autoload.php";




$stack = new Stack();

$stack->addEventRun(
    (new Floor(1))
    ->addPeople(new People(ElevatorPanel::DIRECTIONS_UP, 2))
);
$stack->addEventRun(
    (new Floor(2))
        ->addPeople(new People(ElevatorPanel::DIRECTIONS_UP, 3))
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
//$elevator->AddEventStop(3);

dbg(date("H:i:s"));
$elevator->run();
dbg(date("H:i:s"));
sleep(2);
//$elevator->removeStop();
dbg(date("H:i:s"));

function dbg($parr) {
    echo '<pre>';
    var_export($parr);
    echo '</pre>';
}