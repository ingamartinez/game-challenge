<?php

use PHPUnit\Framework\TestCase;
use Uniqoders\Game\Console\Game;

class GameTest extends TestCase
{
    /**
     * @test
     */
    public function it_min_victories_should_be_optionals()
    {
        $game = new Game('Alejandro');
        $this->assertSame($game->getMinVictories(), 3);
    }

    public function it_should_set_min_victories()
    {
        $game1 = new Game('Alejandro', 10);
        $this->assertSame($game1->getMinVictories(), 10);

        $game2 = new Game('Alejandro', 20);
        $this->assertSame($game2->getMinVictories(), 20);
    }

    /**
     * @test
     */
    public function it_max_rounds_should_be_optionals()
    {
        $game = new Game('Alejandro', 10);
        $this->assertSame($game->getMaxRounds(), 5);
    }

    /**
     * @test
     */
    public function it_should_set_max_rounds()
    {
        $game2 = new Game('Alejandro', 10, 20);
        $this->assertSame($game2->getMaxRounds(), 20);

        $game2 = new Game('Alejandro', 10, 10);
        $this->assertSame($game2->getMaxRounds(), 10);
    }

    /**
     * @test
     * @dataProvider dataPlayer
     */
    public function it_player_should_win($weapon, $computerWeapon)
    {
        $game = new Game('Alejandro');

        $result = $game->calculateWinner($weapon, $computerWeapon);

        $this->assertEquals("Alejandro [$weapon] wins!", $result['winner']);
    }

    /**
     * @test
     * @dataProvider dataComputer
     */
    public function it_computer_should_win($weapon, $computerWeapon)
    {
        $game = new Game('Alejandro');

        $result = $game->calculateWinner($weapon, $computerWeapon);

        $this->assertEquals("Computer [$computerWeapon] wins!", $result['winner']);
    }

    /**
     * @test
     * @dataProvider dataDraw
     */
    public function it_should_draw($weapon, $computerWeapon)
    {
        $game = new Game('Alejandro');

        $result = $game->calculateWinner($weapon, $computerWeapon);

        $this->assertEquals("Draw!", $result['winner']);
    }

    /**
     * @test
     */
    public function it_should_get_stats()
    {
        $game = new Game('Alejandro', 5, 5);

        $game->calculateWinner('Scissors', 'Paper');
        $game->calculateWinner('Scissors', 'Lizard');
        $game->calculateWinner('Rock', 'Paper');
        $game->calculateWinner('Rock', 'Spock');
        $game->calculateWinner('Paper', 'Paper');

        $scoreBoard = $game->getScoreBoard();

        $expected = [
            'headers' => ['Player', 'Victory', 'Draw', 'Defeat'],
            'values' => [
                [
                    'Player' => 'Alejandro',
                    'Victory' => 2,
                    'Draw' => 1,
                    'Defeat' => 2
                ],
                [
                    'Player' => 'Computer',
                    'Victory' => 2,
                    'Draw' => 1,
                    'Defeat' => 2
                ]
            ]
        ];

        $this->assertSame($expected, $scoreBoard);
    }

    /**
     * @test
     */
    public function it_should_return_winner_or_end_of_rounds()
    {
        //Win Player
        $game = new Game('Alejandro', 2, 2);
        $game->calculateWinner('Scissors', 'Paper');
        $game->calculateWinner('Scissors', 'Lizard');

        $result = $game->thereIsAWinner();
        $this->assertSame(true, $result);

        //Win Computer
        $game = new Game('Alejandro', 2, 2);
        $game->calculateWinner('Rock', 'Paper');
        $game->calculateWinner('Rock', 'Spock');

        $result = $game->thereIsAWinner();
        $this->assertSame(true, $result);

        //Draw
        $game = new Game('Alejandro', 2, 2);
        $game->calculateWinner('Rock', 'Rock');
        $game->calculateWinner('Spock', 'Spock');

        $result = $game->thereIsAWinner();
        $this->assertSame(true, $result);
    }

    public function dataPlayer(): array
    {
        return array(
            array('Scissors', 'Paper'),
            array('Scissors', 'Lizard'),
            array('Paper', 'Rock'),
            array('Paper', 'Spock'),
            array('Rock', 'Lizard'),
            array('Rock', 'Scissors'),
            array('Lizard', 'Spock'),
            array('Lizard', 'Paper'),
            array('Spock', 'Scissors'),
            array('Spock', 'Rock'),
        );
    }

    public function dataComputer(): array
    {
        return array(
            array('Scissors', 'Spock'),
            array('Scissors', 'Rock'),
            array('Paper', 'Scissors'),
            array('Paper', 'Lizard'),
            array('Rock', 'Paper'),
            array('Rock', 'Spock'),
            array('Lizard', 'Rock'),
            array('Lizard', 'Scissors'),
            array('Spock', 'Lizard'),
            array('Spock', 'Paper'),
        );
    }

    public function dataDraw(): array
    {
        return array(
            array('Scissors', 'Scissors'),
            array('Paper', 'Paper'),
            array('Rock', 'Rock'),
            array('Lizard', 'Lizard'),
            array('Spock', 'Spock'),
        );
    }

}