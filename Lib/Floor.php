<?php
/**
 * Created by PhpStorm.
 * User: kayza_000
 * Date: 04.08.2019
 * Time: 10:55
 */

namespace Lib;


class Floor
{
   private $peoples = null, $floor = null;
    
    public function __construct($floor)
    {
        $this->floor = $floor; 
    }

    public function addPeople(People $people)
    {
        $this->peoples = $people;
        
        return $this;
    }

    public function getFloor()
    {
        return $this->floor;
    }


    /**
     * @return People
     */
    public function getPeoples()
    {
        return $this->peoples;
    }
}