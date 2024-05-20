<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bots\BIP_BOT_USERNAME\Stages;
use Bip\App\Stage;


class StartStage extends Stage{
    public function controller(){
        route('start')->onMessageText('/start');
    }
    public function start(){
        msg("it works! what is your name ?");
        bindNode('showName');
    }
    public function showName()
    {
        msg("Welcome ".text()." !");
        closeNode();
    }
}
