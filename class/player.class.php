<?php

class Player
{

    private $odds;

    public function __construct()
    {
        $this->odds = mt_rand(15, 100) / 100;
    }

    public function getOdds()
    {
        return $this->odds;
    }
}
