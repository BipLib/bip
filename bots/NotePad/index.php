<?php

require __DIR__."/../../vendor/autoload.php";
require 'config.php';

use Bip\Bot;
use Bots\NotePad\Stages\StartStage;



Bot::init(new StartStage());
Bot::run();
