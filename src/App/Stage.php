<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\App;


use Bip\Bot;
use Bip\Telegram\Telegram;

abstract class Stage
{
    /**
     * next node name.
     * @var string
     */
    public string $_node;
    /**
     * the absolute address of stage.
     * @var string
     */
    public string $_call;

    /**
     * in every run of the Stage, controller automatically called.
     */
    public abstract function controller(Bot $bot,Telegram $telegram);

    public function __construct()
    {
        $this->_call = get_class($this);
    }
}