<?php

class League
{

    const NUMBER_OF_MATCH_TO_WIN = 4;

    private $divisions;

    public function __construct(array $divisions)
    {
        $this->divisions = $divisions;
    }

    public function getDivisions()
    {
        return $this->divisions;
    }
}
