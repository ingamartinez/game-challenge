<?php

namespace Uniqoders\Game\Console\Models;

use Uniqoders\Game\Console\Interfaces\PlayableInterface;
use Uniqoders\Game\Console\Interfaces\ToArrayInterface;

/**
 *
 */
class Player implements ToArrayInterface, PlayableInterface
{
    /**
     * @var string
     */
    protected string $name;
    /**
     * @var Stats
     */
    protected Stats $stats;
    /**
     * @var Weapon
     */
    protected Weapon $weapon;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->stats = new Stats();
    }

    /**
     * @param Player $player
     * @return int
     */
    public function fight(Player $player): int
    {
        $totalWeapons = Weapon::availableWeapons()->count();
        return ($this->weapon->getDamage() - $player->weapon->getDamage() + $totalWeapons) % $totalWeapons;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'Player' => $this->name,
            'Victory' => $this->stats()->getVictories(),
            'Draw' => $this->stats()->getDraws(),
            'Defeat' => $this->stats()->getDefeats()
        ];
    }

    /**
     * @return Stats
     */
    public function stats(): Stats
    {
        return $this->stats;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return void
     */
    public function win()
    {
        $this->stats->win();
    }

    /**
     * @return void
     */
    public function defeat()
    {
        $this->stats->defeat();
    }

    /**
     * @return void
     */
    public function draw()
    {
        $this->stats->draw();
    }

    /**
     * @return Weapon
     */
    public function getWeapon(): Weapon
    {
        return $this->weapon;
    }

    /**
     * @param Weapon $weapon
     * @return void
     */
    public function setWeapon(Weapon $weapon): void
    {
        $this->weapon = $weapon;
    }


}