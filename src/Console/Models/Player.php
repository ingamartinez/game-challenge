<?php

namespace Uniqoders\Game\Console\Models;

use Uniqoders\Game\Console\Interfaces\ToArrayInterface;

class Player implements ToArrayInterface
{
    protected string $name;
    protected Stats $stats;

    public function __construct($name)
    {
        $this->name = $name;
        $this->stats = new Stats();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function win()
    {
        $this->stats->win();
    }

    public function defeat()
    {
        $this->stats->defeat();
    }

    public function draw()
    {
        $this->stats->draw();
    }

    public function toArray(): array
    {
        return [
            'Player' => $this->name,
            'Victory' => $this->stats()->getVictories(),
            'Draw' => $this->stats()->getDraws(),
            'Defeat' => $this->stats()->getDefeats()
        ];
    }

    public function stats(): Stats
    {
        return $this->stats;
    }
}