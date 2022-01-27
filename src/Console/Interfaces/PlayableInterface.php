<?php

namespace Uniqoders\Game\Console\Interfaces;

use Uniqoders\Game\Console\Models\Player;

interface PlayableInterface
{
    public function fight(Player $player): int;
}