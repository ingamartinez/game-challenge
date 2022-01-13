<?php
declare(strict_types=1);

use Uniqoders\Game\Console\GameCommand;

require_once('vendor/autoload.php');

$app = new Symfony\Component\Console\Application('Rock, Paper, Scissors', '1.0.0');
$app->add(new GameCommand());

$app->run();