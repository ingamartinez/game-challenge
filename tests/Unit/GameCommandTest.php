<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\Console\Tester\CommandTester;
use Uniqoders\Game\Console\GameCommand;

class GameCommandTest extends TestCase
{
    public Application $application;

    protected function setUp(): void
    {
        $application = new Application();
        $application->add(new GameCommand());
        $this->application = $application;
    }

    /**
     * @test
     */
    public function it_should_run(){

        $command = $this->application->find('game');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['Rock']);

        $commandTester->execute([
            'name' => 'Alejandro',
            '--max-rounds' => 1,
            '--min-victories' => 1
        ]);

        $commandTester->assertCommandIsSuccessful();
    }

    /**
     * @test
     */
    public function it_should_only_receive_valid_input(){
        $this->expectException(MissingInputException::class);

        $command = $this->application->find('game');
        $commandTester = new CommandTester($command);

        $commandTester->setInputs(['Invalid']);

        $commandTester->execute([
            'name' => 'Alejandro',
            '--max-rounds' => 1,
            '--min-victories' => 1
        ]);
    }

}