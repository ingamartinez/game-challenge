<?php

namespace Uniqoders\Game\Console;

use Uniqoders\Game\Console\Models\Player;
use Uniqoders\Game\Console\Models\Weapon;

class Game
{
    protected Player $player;
    protected Player $computer;
    protected array $weapons;
    protected int $max_rounds;
    protected int $actual_rounds;
    protected int $min_victories;

    public function __construct(string $player_name, int $min_victories = 3, int $max_rounds = 5)
    {
        $this->player = new Player($player_name);
        $this->computer = new Player('Computer');
        $this->max_rounds = $max_rounds;
        $this->min_victories = $min_victories;
        $this->actual_rounds = 0;
        $this->weapons = Weapon::createFromArray(Weapon::availableWeapons());
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
        $humanWeapon = $this->getWeaponByText($firstWeapon);
        $computerWeapon = $secondWeapon ? $this->getWeaponByText($secondWeapon) : $this->getRandomWeapon();

        $result = ($humanWeapon->getDamage() - $computerWeapon->getDamage() + count($this->weapons)) % count($this->weapons);

        $response = [
            'human_choice' => "You have just selected: [$humanWeapon]",
            'computer_choice' => "Computer has just selected: [$computerWeapon]"
        ];

        if ($result === 0) {
            $response['winner'] = "Draw!";
            $this->player->draw();
            $this->computer->draw();
        } elseif ($result % 2 === 0) {
            $response['winner'] = $this->player->getName() . " [$humanWeapon] wins!";
            $this->player->win();
            $this->computer->defeat();
        } elseif ($result % 2 !== 0) {
            $response['winner'] = $this->computer->getName() . " [$computerWeapon] wins!";
            $this->player->defeat();
            $this->computer->win();
        }
        $this->sumRounds();
        return $response;
    }

    public function getWeaponByText(string $weaponText): Weapon
    {
        $weapon = array_filter(
            $this->weapons,
            function (Weapon $filterWeapon) use ($weaponText) {
                return $filterWeapon->getName() === $weaponText;
            }
        );
        return array_pop($weapon);
    }

    public function getRandomWeapon(): Weapon
    {
        $randomWeapon = (array_rand($this->weapons));
        return $this->weapons[$randomWeapon];
    }

    public function sumRounds()
    {
        $this->actual_rounds++;
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
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
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