# Console game Rock, Paper, Scissors, Lizard, Spock 
This is a challenge game wehere the player will play the well known Rock, Paper, Scissors but with the twist presented in the big bang theory show including 2 new options: Spock and Lizzard.

Install dependencies:
```
composer install
```

Run the game:
```
php console game <your name>
```

You can see a list of options using `php console game -h`
```
Description:
  New game: you vs computer

Usage:
  game [options] [--] [<name>]

Arguments:
  name                                 What is your name? [default: "Player 1"]

Options:
      --min-victories                  Minimum rounds to win [default: 3]
      --max-rounds                     Maximum rounds [default: 5]
  -h, --help                           Display help for the given command. When no command is given display help for the list command
  -q, --quiet                          Do not output any message
  -V, --version                        Display this application version
      --ansi|--no-ansi                 Force (or disable --no-ansi) ANSI output
  -n, --no-interaction                 Do not ask any interactive question
  -v|vv|vvv, --verbose                 Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
## Game Rules
```
[SCISSORS]  cuts        [PAPER]
[PAPER]     covers      [ROCK]
[ROCK]      crushes     [LIZARD]
[LIZARD]    poisons     [SPOCK]
[SPOCK]     smashes     [SCISSORS]
[SCISSORS]  decapitates [LIZARD]
[LIZARD]    eats        [PAPER]
[PAPER]     disproves   [SPOCK]
[SPOCK]     vaporizes   [ROCK]
[ROCK]      crushes     [SCISSORS]
```
## Game Explanation
<img src="https://user-images.githubusercontent.com/17319616/149631761-0c39f55a-e659-4da4-ad73-b57d5f5f85c6.png" width="600" height="auto">

As seen in the previous image, a pattern can be observed: moving forward positions in an odd number you loose, and moving forward in an even number you win.

Choosing an option (eg ROCK) this one would win against [LIZZARD], but he would lose against [SPOCK].

Having found this pattern, the designed solution was to determinate how many positions are between the player and computer elections.

This is achieved using the arithmetic modulus formula, which would be represented by taking the previous example:
```
(2-3) mod5 = 4 // [ROCK] vs [LIZZARD]
(2-4) mod5 = 3 // [LIZZARD] vs [SPOCK]
```
This gives us another pattern: when the result is an even number you win and when it is an odd number you lose.

Since there is no arithmetic modulus function in PHP, it would be represented as follows:
```
(human_option - computer_option + total_options) % total_options
```
In this way, the formula will give us how many positions there are, even if we take [SCISSORS] vs [SPOCK] `[0-4]` which would be:
```
(0-4+5) % 5 = 1 // [SPOCK] smashes [SCISSORS]
```
