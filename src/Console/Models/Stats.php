<?php
namespace Uniqoders\Game\Console\Models;

class Stats
{
    public int $draw;
    public int $victory;
    public int $defeat;

    public function __construct() {
        $this->draw = 0;
        $this->victory = 0;
        $this->defeat = 0;
    }

    public function getVictories(): int
    {
        return $this->victory;
    }

    public function getDefeats(): int
    {
        return $this->defeat;
    }

    public function getDraws(): int
    {
        return $this->draw;
    }

    public function win() {
        $this->victory++;
    }

    public function defeat() {
        $this->defeat++;
    }

    public function draw () {
        $this->draw++;
    }

}