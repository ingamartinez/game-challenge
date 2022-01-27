<?php

namespace Uniqoders\Game\Console;

use Tightenco\Collect\Support\Collection;
use Uniqoders\Game\Console\Models\Player;
use Uniqoders\Game\Console\Models\Weapon;

/**
 *
 */
class Game
{
    /**
     * @var Player
     */
    protected Player $player;
    /**
     * @var Player
     */
    protected Player $computer;
    /**
     * @var Collection
     */
    protected Collection $weapons;
    /**
     * @var int
     */
    protected int $max_rounds;
    /**
     * @var int
     */
    protected int $actual_rounds;
    /**
     * @var int
     */
    protected int $min_victories;

    /**
     * @param string $player_name
     * @param int $min_victories
     * @param int $max_rounds
     */
    public function __construct(string $player_name, int $min_victories = 3, int $max_rounds = 5)
    {
        $this->player = new Player($player_name);
        $this->computer = new Player('Computer');
        $this->max_rounds = $max_rounds;
        $this->min_victories = $min_victories;
        $this->actual_rounds = 0;
        $this->weapons = Weapon::create(Weapon::availableWeapons());
    }

    /**
     * Method called each time a player select an option from the menu
     * It calculates the winner of the round
     *
     * @param string $firstWeapon
     * @param string|null $secondWeapon
     * @return string[]
     */
    public function calculateWinner(string $firstWeapon, string $secondWeapon = null): array
    {
        $this->player->setWeapon($this->getWeaponByText($firstWeapon));
        $this->computer->setWeapon($secondWeapon ? $this->getWeaponByText($secondWeapon) : $this->getRandomWeapon());

        $result = $this->player->fight($this->computer);

        $this->sumRounds();

        return [
            'human_choice' => "You have just selected [{$this->player->getWeapon()}]",
            'computer_choice' => "Computer has just selected [{$this->computer->getWeapon()}]",
            'winner' => $this->rules($result)
        ];
    }

    /**
     * Find a weapon by his name
     *
     * @param string $weaponText
     * @return Weapon
     */
    public function getWeaponByText(string $weaponText): Weapon
    {
        return $this->weapons->filter(function (Weapon $weapon) use ($weaponText) {
            return $weapon->getName() === $weaponText;
        })->first();
    }

    /**
     * Return a random weapon from the list
     *
     * @return Weapon
     */
    public function getRandomWeapon(): Weapon
    {
        return $this->weapons->random();
    }

    /**
     * Add +1 to the rounds
     *
     * @return void
     */
    public function sumRounds()
    {
        $this->actual_rounds++;
    }

    /**
     * Rules for determinate the winner
     *
     * @param int $result
     * @return string
     */
    public function rules(int $result): string
    {
        if ($result === 0) {
            $this->player->draw();
            $this->computer->draw();
            return "Draw!";
        }

        if ($result % 2 === 0) {
            $this->player->win();
            $this->computer->defeat();
            return $this->player->getName() . " [{$this->player->getWeapon()}] wins!";
        }

        $this->player->defeat();
        $this->computer->win();
        return $this->computer->getName() . " [{$this->computer->getWeapon()}] wins!";
    }

    /**
     * Method that prepare the data of the
     * two players (Player and Computer) to show the score
     *
     * @return array
     */
    public function getScoreBoard(): array
    {
        return [
            'headers' => ['Player', 'Victory', 'Draw', 'Defeat'],
            'values' => [
                $this->player->toArray(),
                $this->computer->toArray(),
            ]
        ];
    }

    /**
     * Method to validate if there is a winner
     *
     * @return bool
     */
    public function thereIsAWinner(): bool
    {
        if ($this->player->stats()->getVictories() === $this->min_victories) {
            return true;
        }
        if ($this->computer->stats()->getVictories() === $this->min_victories) {
            return true;
        }
        if ($this->actual_rounds === $this->max_rounds) {
            return true;
        }
        return false;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Player
     */
    public function getComputer(): Player
    {
        return $this->computer;
    }

    /**
     * @return Collection
     */
    public function getWeapons(): Collection
    {
        return $this->weapons;
    }

    /**
     * @return int
     */
    public function getMaxRounds(): int
    {
        return $this->max_rounds;
    }

    /**
     * @return int
     */
    public function getActualRounds(): int
    {
        return $this->actual_rounds;
    }

    /**
     * @return int
     */
    public function getMinVictories(): int
    {
        return $this->min_victories;
    }


}