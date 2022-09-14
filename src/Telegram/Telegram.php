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


use Bip\Telegram\Method\Request;

class Telegram
{
    use Request;

    public function __construct()
    {
        new Update(json_decode(file_get_contents('php://input')));
    }

    /**
     * Shortcut for sendMessage.
     * @param string $text
     */
    public function msg(string $text){
        $this->call("sendMessage",[
            'chat_id'=>Update::get()->message->chat->id,
            'text'=>$text,
        ]);
    }
}