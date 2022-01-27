<?php

namespace Uniqoders\Game\Console\Models;

use Tightenco\Collect\Support\Collection;

/**
 *
 */
class Weapon
{
    /**
     * @param string $name
     * @param int $damage
     */
    public function __construct(protected string $name, protected int $damage)
    {
    }

    /**
     * @param Collection $weapons
     * @return Collection
     */
    public static function create(Collection $weapons): Collection
    {
        $availableWeapons = Weapon::availableWeapons();

        return $weapons->map(function ($weapon, $key) use ($availableWeapons) {
            if (!$availableWeapons->contains($weapon)) {
                throw new \Exception("Weapon doesn't exists");
            }
            return new Weapon($weapon, $key);
        });
    }

    /**
     * @return Collection
     */
    public static function availableWeapons(): Collection
    {
        return collect(["Scissors", "Paper", "Rock", "Lizard", "Spock"]);
    }

    /**
     * @return int
     */
    public function getDamage(): int
    {
        return $this->damage;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}