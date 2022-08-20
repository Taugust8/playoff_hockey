<?php

class Division
{

    const POLES = ["East", "West"];
    const NUMBER_OF_TEAMS = 8;

    private $pole;
    private $teams;
    private $actualRound;
    private $winner;

    public function __construct(string $pole, array $teams)
    {
        $this->pole = $pole;
        $this->teams = $teams;
        $this->actualRound = 0;
        $this->winner = null;
    }

    public function getPole()
    {
        return $this->pole;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function getActualRound()
    {
        return $this->actualRound;
    }

    public function setActualRound($actualRound)
    {
        $this->actualRound = $actualRound;
    }

    public function getWinner()
    {
        return $this->winner;
    }

    public function setWinner($winner)
    {
        $this->winner = $winner;
    }
}
