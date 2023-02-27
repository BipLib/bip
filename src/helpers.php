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
            \Bip\Telegram\Call::sendMessage(
                chat_id : \Bip\Telegram\Webhook::getObject()->message->chat->id,
                text : $text,
                parse_mode : 'MARKDOWN'
            );
        }
}
if (! function_exists('updateObj')){
    /**
     * get update object.
     * @return object
     */
    function updateObj(): object
    {
        return \Bip\Telegram\Webhook::getObject();
    }

}
if (! function_exists('update')){
    /**
     * get update.
     * @return object
     */
    function update(): object
    {
        return \Bip\Telegram\Webhook::get();
    }

}
// Bot functions
if (! function_exists('bindNode')) {
    /**
     * bind node to current stage.
     * @param string $node
     * @return void
     */
    function bindNode(string $node): void
    {
        \Bip\Bot::bindNode($node);
    }
}
if (! function_exists('closeNode')){
    /**
     * close the current node.(it will bind to `default` node, `default` node if not exists, it will be ignored)
     * @return void
     */
    function closeNode(): void
    {
        \Bip\Bot::closeNode();
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
if (! function_exists('dd')){
    /**
     * Debug and die.
     * @param mixed $var
     * @return void
     */
   function dd(mixed $var): void
    {
        msg(var_export($var, true));
        die();
    }
}

