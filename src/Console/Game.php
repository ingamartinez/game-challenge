<?php

namespace Uniqoders\Game\Console;

use MathieuViossat\Util\ArrayToTextTable;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use Uniqoders\Game\Console\Models\Player;

class Game
{
    protected Player $player;
    protected Player $computer;
    protected array $options = ["Scissors" => 0, "Paper" => 1, "Rock" => 2, "Lizard" => 3, "Spock" => 4];
    protected int $max_rounds;
    protected int $actual_rounds;
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

    public function __construct(string $player_name, int $max_rounds = 5) {
        $this->player = new Player($player_name);
        $this->computer = new Player('Computer');
        $this->max_rounds = $max_rounds;
        $this->actual_rounds = 0;
    }

    public function start() {
        $this->createGameCover();
    }

    protected function createGameCover(): CliMenu
    {
        $menu = (new CliMenuBuilder)
            ->setTitle('Rock Paper Scissors Lizard Spock - Game')
            ->addStaticItem('')
            ->addAsciiArt($this->artCover,'center')
            ->addItem('Start Game', \Closure::fromCallable([$this, 'createGame']))
            ->setBorder(1, 2, 'yellow')
            ->setPadding(2, 4)
            ->setMarginAuto()
            ->disableDefaultItems()
            ->build();
        $menu->open();
        return $menu;
    }

    protected function createGame(CliMenu $menuCover) {
        $menuCover->close();

        (new CliMenuBuilder)
        ->setTitle('Rock, Paper, Scissors, Lizard, Spock Game')
        ->addStaticItem('Please choose a option:')
        ->addLineBreak()
        ->addItem('Rock', \Closure::fromCallable([$this, 'calculateWinner']))
        ->addItem('Paper', \Closure::fromCallable([$this, 'calculateWinner']))
        ->addItem('Scissors', \Closure::fromCallable([$this, 'calculateWinner']))
        ->addItem('Lizard', \Closure::fromCallable([$this, 'calculateWinner']))
        ->addItem('Spock', \Closure::fromCallable([$this, 'calculateWinner']))
        ->addLineBreak('-')
        ->setBorder(1, 2, 'yellow')
        ->setPadding(2, 4)
        ->setMarginAuto()
        ->build()
        ->open();
    }

    protected function calculateWinner(CliMenu $menu){
        $optionHumanText = $menu->getSelectedItem()->getText();
        $optionHumanValue = $this->options[$optionHumanText];

        $optionComputerText = array_rand($this->options);
        $optionComputerValue = $this->options[$optionComputerText];
        $win = ($optionHumanValue - $optionComputerValue + count($this->options)) % count($this->options);

        $this->printRoundWinner($menu, "You have just selected: [$optionHumanText]", 'cyan');
        $this->printRoundWinner($menu, "Computer has just selected: [$optionComputerText]", 'cyan');

        if ($win%2 === 0) {
            $this->printRoundWinner($menu, $this->player->getName() . " [$optionHumanText] wins!", 'green');
            $this->player->win();
            $this->computer->defeat();
        } elseif ($win%2 !== 0) {
            $this->printRoundWinner($menu, $this->computer->getName() . " [$optionComputerText] wins!!", 'yellow');
            $this->player->defeat();
            $this->computer->win();
        } elseif ($win === 0) {
            $this->printRoundWinner($menu, "Draw!", 'yellow');
            $this->player->draw();
            $this->computer->draw();
        }
        $this->max_rounds++;
        $this->thereIsAWinner($menu);

    }

    protected function printRoundWinner(CliMenu $menu, $text, $bg='default') {
        $flash = $menu->flash($text);
        $flash->getStyle()->setBg($bg);
        $flash->display();
    }

    protected function getScoreBoard(): string
    {
        $data = [
            $this->computer->getAscii(),
            $this->player->getAscii(),
        ];
        $renderer = new ArrayToTextTable($data);
        return $renderer->getTable();
    }

    protected function thereIsAWinner(CliMenu $menu) {
        if ($this->player->stats()->getVictories() >= 3) {
            $menu->close();
            $this->showScoreBoard();
        }
        if ($this->computer->stats()->getVictories() >= 3) {
            $menu->close();
            $this->showScoreBoard();
        }
        if ($this->actual_rounds >= $this->max_rounds) {
            $menu->close();
            $this->showScoreBoard();
        }
    }

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