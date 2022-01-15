# Console game Rock, Paper, Scissors, Lizard, Spock 
This is a challenge game when the player will play the famous Rock, Paper, Scissors the Big Bang Theory version.

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
<img src="https://user-images.githubusercontent.com/17319616/149609552-004da205-84f1-4830-9c2a-880cfc982201.png" width="600" height="auto">

Keeping in mind the previous image, a pattern can be seen: Every one element you win and every two elements you lose.

Example: If we take [ROCK], the next element he would beat is [LIZZARD], but he would lose against [SPOCK].

Having found this pattern, the designed solution was to find how many positions there are of difference between what the player chose and what the machine chose.

This is achieved using the arithmetic modulus formula, which would be represented by taking the previous example:
```
(2-3)mod5=4` y `(2-4)mod5=3
```
This gives us another pattern and that is that when the result gives an even number you win and when it gives an odd number you lose.

Since there is no arithmetic modulus function in PHP, it would be represented as follows:
```
(human_option - computer_option + total_options) % total_options
```
In this way, it will always give us how many positions there are, even if we take [SCISSORS] vs [SPOCK] which would be:
```
(0-4+5)%5 = 1 // [SPOCK] smashes [SCISSORS]
```
