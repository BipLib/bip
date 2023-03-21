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
    /**
     * route to node if Message->text is equal to $text.
     * @param string $text
     * @return $this
     */
    public function onMessageText(string $text): RouteRule
    {
        $this->result = $this->result && (isset(Webhook::getObject()->message->text) && Webhook::getObject()->message->text == $text);
        return $this;
    }

    /**
     * route to node if CallbackQuery->data is equal to $data.
     * @param string $data
     * @return $this
     */
    public function onCallbackData(string $data): RouteRule
    {
        $this->result = $this->result && (isset(Webhook::getObject()->callback_query->data) && Webhook::getObject()->callback_query->data == $data);
        return $this;
    }
    public function __destruct()
    {
        if ($this->result) {
            Bot::setRoutedNode(Bot::getToBeRoutedNode());
            Bot::bindNode(Bot::getToBeRoutedNode());
            self::$isRouted = true;
        }
    }


}