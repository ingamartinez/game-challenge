<?php

namespace Uniqoders\Game\Console;

use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class GameCommand extends Command
{

    protected Game $game;
    protected InputInterface $input;
    protected OutputInterface $output;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->game = new Game(
            $input->getArgument('name'),
            $input->getOption('min-victories'),
            $input->getOption('max-rounds')
        );
        $this->input = $input;
        $this->output = $output;
        $this->start();
        return Command::SUCCESS;
    }

    public function start() {
        $this->output->writeln([
            'Rock Paper Scissors Lizard Spock - Game',
        ]);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select a weapon',
            ['Scissors', 'Paper', 'Rock', 'Lizard', 'Spock']
        );
        $question->setErrorMessage('Weapon %s is not valid.');

        while (!$this->game->thereIsAWinner()) {
            $weapon = $helper->ask($this->input, $this->output, $question);
            $response = $this->game->calculateWinner($weapon);
            $this->printWinners($response);
        }

        $this->printScoreBoard($this->game->getScoreBoard());
    }

    public function printWinners(array $response) {
        foreach ($response as $print) {
            $this->output->writeln([
                $print,
            ]);
        }
    }

    public function printScoreBoard($scoreBoard) {
        $table = new Table($this->output);
        $table
            ->setHeaders($scoreBoard['headers'])
            ->setRows($scoreBoard['values'])
        ;
        $table->render();
    }
}