<?php

require_once "player.class.php";

class Team
{

    const LETTERS = "ABCDEFGH";
    const NUMBER_OF_PLAYERS = 21;

    private $letter;
    private $players;
    private $eliminated;

    public function __construct(string $letter, array $players)
    {
        $this->letter = $letter;
        $this->players = $players;
        $this->eliminated = false;
    }

    public function getOddsTeam()
    {
        $oddsTeam = 0;
        foreach ($this->players as $player) {
            $oddsTeam += $player->getOdds();
        }
        return round($oddsTeam / sizeof($this->players), 2);
    }

    public function getLetter()
    {
        return $this->letter;
    }

    public function getEliminated()
    {
        return $this->eliminated;
    }

    public function setEliminated($eliminated)
    {
        $this->eliminated = $eliminated;
    }
}
