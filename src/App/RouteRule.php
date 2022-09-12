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

class RouteRule
{
    private bool $result = true;
    private Bot $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function when(bool $condition): RouteRule
    {
        $this->result = $this->result && $condition;
        return $this;
    }

    public function __destruct()
    {
        if ($this->result)
            $this->bot->setRoutedNode($this->bot->getToBeRoutedNode());

    }


}