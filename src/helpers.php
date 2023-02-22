<?php
// main functions
if (! function_exists('msg')) {
    /**
     * short function for sending message.
     * @param string $text
     * @return void
     */
    function msg(string $text) : void
        {
            \Bip\Telegram\Telegram::sendMessage(
                chat_id : \Bip\Telegram\Webhook::getObject()->message->chat->id,
                text : $text,
            );
        }
}
if (! function_exists('update')){
    /**
     * get update object.
     * @return object
     */
    function update(): object
    {
        return \Bip\Telegram\Webhook::getObject();
    }

}
// Bot functions
if (! function_exists('bindNode')) {
    /**
     * @param string $node
     * @return void
     */
    function bindNode(string $node): void
    {
        \Bip\Bot::bindNode($node);
    }
}
if (! function_exists('changeStage')) {
    /**
     * @param string $newStage
     * @return void
     */
    function changeStage(string $newStage): void
    {
        \Bip\Bot::changeStage($newStage);
    }
}
if (! function_exists('route')) {
    /**
     * @param string $node
     * @return \Bip\App\RouteRule
     */
    function route(string $node): \Bip\App\RouteRule
    {
        return \Bip\Bot::route($node);
    }
}