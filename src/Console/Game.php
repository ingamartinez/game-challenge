<?php

namespace Uniqoders\Game\Console;

use Closure;
use MathieuViossat\Util\ArrayToTextTable;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use Uniqoders\Game\Console\Models\Player;

class Game
{
    protected Player $player;
    protected Player $computer;
    protected array $options = ["Scissors" => 0, "Paper" => 1, "Rock" => 2, "Lizard" => 3, "Spock" => 4];
    protected int $max_rounds;
    protected int $actual_rounds;
    protected int $min_victories;
    protected string $artCover = "               .
              .:.
             .:::.
            .:::::.
        ***.:::::::.***
   *******.:::::::::.*******       
 ********.:::::::::::.********     
********.:::::::::::::.********    
*******.::::::'***`::::.*******    
******.::::'*********`::.******    
 ****.:::'*************`:.****
   *.::'*****************`.*
   .:'  ***************    .
  .
";

    public function __construct(string $player_name, int $min_victories = 3,  int $max_rounds = 5) {
        $this->player = new Player($player_name);
        $this->computer = new Player('Computer');
        $this->max_rounds = $max_rounds;
        $this->min_victories = $min_victories;
        $this->actual_rounds = 0;
    }

    /**
     * Method that start the game
     *
     * @return void
     * @throws InvalidTerminalException
     */
    public function start() {
        $this->createGameCover();
    }

    /**
     * Show an ASCII image as cover
     *
     * @return void
     * @throws InvalidTerminalException
     */
    protected function createGameCover()
    {
        (new CliMenuBuilder)
            ->setTitle('Rock Paper Scissors Lizard Spock - Game')
            ->addStaticItem('')
            ->addAsciiArt($this->artCover,'center')
            ->addItem('Start Game', Closure::fromCallable([$this, 'createGame']))
            ->setBorder(1, 2, 'yellow')
            ->setPadding(2, 4)
            ->setMarginAuto()
            ->disableDefaultItems()
            ->build()
        ->open();
    }

    /**
     * Create the menu for the game options
     *
     * @param CliMenu $menuCover
     * @return void
     * @throws InvalidTerminalException
     */
    protected function createGame(CliMenu $menuCover) {
        $menuCover->close();

        (new CliMenuBuilder)
            ->setTitle('Rock, Paper, Scissors, Lizard, Spock Game')
            ->addStaticItem('Please choose a option:')
            ->addLineBreak()
            ->addItem('Rock', Closure::fromCallable([$this, 'calculateWinner']))
            ->addItem('Paper', Closure::fromCallable([$this, 'calculateWinner']))
            ->addItem('Scissors', Closure::fromCallable([$this, 'calculateWinner']))
            ->addItem('Lizard', Closure::fromCallable([$this, 'calculateWinner']))
            ->addItem('Spock', Closure::fromCallable([$this, 'calculateWinner']))
            ->addLineBreak('-')
            ->setBorder(1, 2, 'yellow')
            ->setPadding(2, 4)
            ->setMarginAuto()
            ->build()
        ->open();
    }

    /**
     * Method called each time a player select an option from the menu
     * It calculates the winner of the round
     *
     * @param CliMenu $menu
     * @return void
     * @throws InvalidTerminalException
     */
    protected function calculateWinner(CliMenu $menu){
        $optionHumanText = $menu->getSelectedItem()->getText();
        $optionHumanValue = $this->options[$optionHumanText];

        $optionComputerText = array_rand($this->options);
        $optionComputerValue = $this->options[$optionComputerText];
        $win = ($optionHumanValue - $optionComputerValue + count($this->options)) % count($this->options);

        $this->printRoundWinner($menu, "You have just selected: [$optionHumanText]", 'cyan');
        $this->printRoundWinner($menu, "Computer has just selected: [$optionComputerText]", 'cyan');

        if ($win === 0) {
            $this->printRoundWinner($menu, "Draw!", 'yellow');
            $this->player->draw();
            $this->computer->draw();
        }elseif ($win%2 === 0) {
            $this->printRoundWinner($menu, $this->player->getName() . " [$optionHumanText] wins!", 'green');
            $this->player->win();
            $this->computer->defeat();
        } elseif ($win%2 !== 0) {
            $this->printRoundWinner($menu, $this->computer->getName() . " [$optionComputerText] wins!!", 'yellow');
            $this->player->defeat();
            $this->computer->win();
        }
        $this->actual_rounds++;
        $this->thereIsAWinner($menu);
    }

    /**
     * This method is called to print a text like an alert with
     * the options selected and the winner
     *
     * @param CliMenu $menu
     * @param string $text
     * @param string $bg
     */
    protected function printRoundWinner(CliMenu $menu, string $text, string $bg='default') {
        $flash = $menu->flash($text);
        $flash->getStyle()->setBg($bg);
        $flash->display();
    }

    /**
     * Method that generate an ASCII from the data of the
     * two players (Player and Computer)
     *
     * @return string
     */
    protected function getScoreBoard(): string
    {
        $data = [
            $this->player->getAscii(),
            $this->computer->getAscii(),
        ];
        $renderer = new ArrayToTextTable($data);
        return $renderer->getTable();
    }

    /**
     * Method executed at the end of a round to validate if
     * there is a winner or if rounds are over
     *
     * @param CliMenu $menu
     * @return void
     * @throws InvalidTerminalException
     */
    protected function thereIsAWinner(CliMenu $menu) {
        if ($this->player->stats()->getVictories() === $this->min_victories) {
            $menu->close();
            $this->showScoreBoard();
        }
        if ($this->computer->stats()->getVictories() === $this->min_victories) {
            $menu->close();
            $this->showScoreBoard();
        }
        if ($this->actual_rounds === $this->max_rounds) {
            $menu->close();
            $this->showScoreBoard();
        }
    }

    /**
     * Method that print in console and ASCII table with the data
     * of the players
     * @return void
     * @throws InvalidTerminalException
     */
    protected function showScoreBoard() {
        (new CliMenuBuilder)
            ->setTitle('Rock, Paper, Scissors, Lizard, Spock Game > Winner')
            ->addAsciiArt($this->getScoreBoard(),'center')
            ->addLineBreak('-')
            ->setBorder(1, 2, 'yellow')
            ->setPadding(2, 4)
            ->setMarginAuto()
            ->build()
        ->open();
    }
}