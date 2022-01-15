<?php

namespace Uniqoders\Game\Console;

use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GameCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('game')
            ->setDescription('New game: you vs computer')
            ->addArgument('name', InputArgument::OPTIONAL, 'What is your name?', 'Player 1')
            ->addOption("min-victories", null ,InputOption::VALUE_OPTIONAL, 'Minimum rounds to win', 3)
            ->addOption('max-rounds', null ,InputOption::VALUE_OPTIONAL, 'Maximum rounds', 5);
    }

    /**
     * Instantiate the Game and pass the parameters needed to start the game
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws InvalidTerminalException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $game = new Game(
            $input->getArgument('name'),
            $input->getOption('min-victories'),
            $input->getOption('max-rounds')
        );
        $game->start();
        return 0;
    }
}