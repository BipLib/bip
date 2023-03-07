<?php
// main functions

if (! function_exists('msg')) {
    /**
     * short function for sending message.
     * @param string $text
     * @param array|null $reply_markup
     * @return void
     */
    function msg(string $text,array $reply_markup = null) : void
        {
            \Bip\Telegram\Call::sendMessage(
                chat_id : \Bip\Telegram\Webhook::getObject()->message->chat->id,
                text : $text,
                parse_mode : 'MARKDOWN',
                reply_markup: $reply_markup
            );
        }
}
if(! function_exists('msgr')){
    /**
     * short function for sending message with reply.
     * @param string $text
     * @param array|null $reply_markup
     * @return void
     */
    function msgr(string $text,array $reply_markup = null) : void
    {
        \Bip\Telegram\Call::sendMessage(
            chat_id : \Bip\Telegram\Webhook::getObject()->message->chat->id,
            text : $text,
            parse_mode : 'MARKDOWN',
            reply_to_message_id : \Bip\Telegram\Webhook::getObject()->message->message_id,
            reply_markup: $reply_markup
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
     * @param \Bip\App\Stage $newStage
     * @return void
     */
    function changeStage(\Bip\App\Stage $newStage): void
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
     * Dump and die.
     * @param mixed $var
     * @return void
     */
   function dd(mixed $var): void
    {
        msg(var_export($var, true));
        die();
    }
}

if (! function_exists('peer')){
    /**
     * get peer chat id.
     * @return int
     */
    function peer(): int
    {
        return \Bip\Telegram\Webhook::get()->message->chat->id;
    }
}

