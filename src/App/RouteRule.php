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
use Bip\Telegram\Webhook;

class RouteRule
{
    private static bool $isRouted = false;
    private bool $result = true;

    public function __construct()
    {
        $this->result = !self::$isRouted && $this->result;
    }

    /**
     * add a condition to the route rule.
     * @param bool $condition
     * @return RouteRule
     */
    public function when(bool $condition): RouteRule
    {
        $this->result = $this->result && $condition;
        return $this;
    }
    public function whenMessageTextIs(string $text): RouteRule
    {
        $this->result = $this->result && Webhook::getObject()->message->text == $text;
        return $this;
    }

    public function __destruct()
    {
        if ($this->result) {
            Bot::setRoutedNode(Bot::getToBeRoutedNode());
            self::$isRouted = true;
        }

    }


}