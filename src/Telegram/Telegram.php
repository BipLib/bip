<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\Telegram;


use Bip\App\ConfigFactory;
use Bip\Telegram\Method\Request;

class Telegram
{
    use Request;

    /**
     * Telegram constructor.
     */
    public function __construct()
    {
        $this->setToken(ConfigFactory::get('bot')->get('token'));
    }

    /**
     * Shortcut for sendMessage.
     * @param string $text
     */
    public function msg(string $text){
        $this->call("sendMessage",[
            'chat_id'=>Update::asObject()->message->chat->id,
            'text'=>$text,
        ]);
    }
}