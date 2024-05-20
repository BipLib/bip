<?php

require __DIR__."/../../vendor/autoload.php";
require 'config.php';

use Bip\Bot;
use Bots\BIP_BOT_USERNAME\Stages\StartStage;


# Bot::setMode(Bot::MODE_PROD);
Bot::run(new StartStage());
