<?php

namespace Uniqoders\Game\Console\Models;

class Weapon
{
    public function __construct(protected string $name, protected int $damage)
    {
    }

    public static function availableWeapons(): array
    {
        return ["Scissors", "Paper", "Rock", "Lizard", "Spock"];
    }

    public static function createFromArray(array $weapons): array
    {
        return array_map(function ($key, $weapon) {
            return new Weapon($weapon, $key);
        }, array_keys($weapons), $weapons);
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

}