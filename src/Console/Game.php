<?php

namespace Uniqoders\Game\Console;

use Uniqoders\Game\Console\Models\Player;

class Game
{
    protected Player $player;
    protected Player $computer;
    protected array $options = ["Scissors" => 0, "Paper" => 1, "Rock" => 2, "Lizard" => 3, "Spock" => 4];
    protected int $max_rounds;
    protected int $actual_rounds;
    protected int $min_victories;

    public function __construct(string $player_name, int $min_victories = 3,  int $max_rounds = 5) {
        $this->player = new Player($player_name);
        $this->computer = new Player('Computer');
        $this->max_rounds = $max_rounds;
        $this->min_victories = $min_victories;
        $this->actual_rounds = 0;
    }

    /**
     * Method called each time a player select an option from the menu
     * It calculates the winner of the round
     *
     * @param string $weapon
     * @param string|null $computerWeapon
     * @return string[]
     */
    public function calculateWinner(string $weapon, string $computerWeapon=null): array{
        $optionHumanText = $weapon;
        $optionHumanValue = $this->options[$optionHumanText];

        $optionComputerText = $computerWeapon ?? array_rand($this->options);
        $optionComputerValue = $this->options[$optionComputerText];
        $result = ($optionHumanValue - $optionComputerValue + count($this->options)) % count($this->options);

        $response = [
            'human_choice' => "You have just selected: [$optionHumanText]",
            'computer_choice' => "Computer has just selected: [$optionComputerText]"
        ];

        if ($result === 0) {
            $response['winner'] = "Draw!";
            $this->player->draw();
            $this->computer->draw();
        }elseif ($result%2 === 0) {
            $response['winner'] = $this->player->getName() . " [$optionHumanText] wins!";
            $this->player->win();
            $this->computer->defeat();
        } elseif ($result%2 !== 0) {
            $response['winner'] = $this->computer->getName() . " [$optionHumanText] wins!";
            $this->player->defeat();
            $this->computer->win();
        }
        $this->sumRounds();
        return $response;
    }

    public function sumRounds(){
        $this->actual_rounds++;
    }

    /**
     * Method that generate an ASCII from the data of the
     * two players (Player and Computer)
     *
     * @return array
     */
    public function getScoreBoard(): array
    {
        return [
            'headers' => ['Player', 'Victory', 'Draw', 'Defeat'],
            'values' => [
                $this->player->getAscii(),
                $this->computer->getAscii(),
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