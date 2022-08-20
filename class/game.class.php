<?php

require_once "division.class.php";
require_once "league.class.php";
require_once "player.class.php";
require_once "team.class.php";

class Game
{
    private $output;

    public function __construct()
    {
        $league = new League($this->generateDivisions());
        $winners = [];
        foreach ($league->getDivisions() as $division) {
            $this->concatOutput("\r\nDivision " . $division->getPole() . " :\r\n");
            $this->generateSeries($division);
            $this->concatOutput("\r\n-------------------------------\r\n");
            $winner = $division->getWinner();
            $winners[$division->getPole() . " " . $winner->getLetter()] = ['team' => $winner, 'victories' => 0];
        }
        $this->concatOutput("\r\nFinal ");
        $this->generateMatchs($winners);
        $this->concatOutput("\r\n");
    }

    public function getOutput()
    {
        return $this->output;
    }

    private function concatOutput($string)
    {
        $this->output .= $string;
    }

    private function generateSeries($division)
    {
        $actualRound = $division->getActualRound();
        $teamsAvailable = [];
        foreach ($division->getTeams() as $team) {
            if (!$team->getEliminated()) {
                $teamsAvailable[] = $team;
                $team->setEliminated(true);
            }
        }
        $series = $this->generateTeamsVersus($teamsAvailable);

        $this->concatOutput("Round #" . $actualRound . ":\r\n");

        foreach ($series as $serie) {
            $this->generateMatchs($serie);
        }

        if (sizeof($teamsAvailable) > 2) {
            $division->setActualRound($actualRound + 1);
            $this->generateSeries($division);
        } else {
            foreach ($teamsAvailable as $teamAvailable) {
                if (!$teamAvailable->getEliminated()) {
                    $division->setWinner($teamAvailable);
                }
            }
        }
    }

    private function generateMatchs($serie)
    {
        $matchTeamsLetters = array_keys($serie);

        $this->concatOutput("Serie " . $matchTeamsLetters[0] . " VS " . $matchTeamsLetters[1] . " - Winner : ");

        $oddsFirstTeam =  $serie[$matchTeamsLetters[0]]["team"]->getOddsTeam();
        $oddsSecondTeam = $serie[$matchTeamsLetters[1]]["team"]->getOddsTeam();
        $serie[$matchTeamsLetters[0]]['probability'] = $oddsFirstTeam / ($oddsFirstTeam + $oddsSecondTeam);
        $serie[$matchTeamsLetters[1]]['probability'] = $oddsSecondTeam / ($oddsSecondTeam + $oddsFirstTeam);

        $teamHaveGoodNumberOfVictories = false;
        while (!$teamHaveGoodNumberOfVictories) {
            foreach ($serie as $letter => $team) {
                if (mt_rand(0, 1000) / 1000 <= (float)$team['probability']) {
                    $serie[$letter]['victories']++;
                    if ($serie[$letter]['victories'] == LEAGUE::NUMBER_OF_MATCH_TO_WIN) {
                        $team['team']->setEliminated(false);
                        $this->concatOutput($letter);
                        $teamHaveGoodNumberOfVictories = true;
                    }
                    break;
                }
            }
        }

        $this->concatOutput(" (" . $serie[$matchTeamsLetters[0]]["victories"] . "/" . $serie[$matchTeamsLetters[1]]["victories"] . ")\r\n");
    }

    private function generateTeamsVersus($teamsAvailable)
    {
        $teamsVersus = [];
        $lettersAlreadyUsed = [];
        for ($i = 1; $i <= (sizeof($teamsAvailable) / 2); $i++) {
            while (in_array(($firstRandomIndex = mt_rand(0, sizeof($teamsAvailable) - 1)), $lettersAlreadyUsed));
            $firstTeam = $teamsAvailable[$firstRandomIndex];
            array_push($lettersAlreadyUsed, $firstRandomIndex);
            while (in_array(($secondRandomIndex = mt_rand(0, sizeof($teamsAvailable) - 1)), $lettersAlreadyUsed));
            $secondTeam = $teamsAvailable[$secondRandomIndex];
            array_push($lettersAlreadyUsed, $secondRandomIndex);
            $teamsVersus[] = [
                $firstTeam->getLetter() => ['team' => $firstTeam, 'victories' => 0],
                $secondTeam->getLetter() => ['team' => $secondTeam, 'victories' => 0]
            ];
        }
        return $teamsVersus;
    }

    private function generatePlayers()
    {
        $players = [];
        for ($i = 0; $i < TEAM::NUMBER_OF_PLAYERS; $i++) {
            $players[] = new Player();
        }
        return $players;
    }

    private function generateTeams()
    {
        $teams = [];
        for ($i = 0; $i < Division::NUMBER_OF_TEAMS; $i++) {
            $letter = TEAM::LETTERS[$i];
            $teams[] = new Team($letter, $this->generatePlayers());
        }
        return $teams;
    }

    private function generateDivisions()
    {
        $divisions = [];
        foreach (Division::POLES as $pole) {
            $divisions[] = new Division($pole, $this->generateTeams());
        }
        return $divisions;
    }
}
