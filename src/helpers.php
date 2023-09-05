<?php
// main functions

if (! function_exists('msg')) {
    /**
     * short function for sending message.
     * @param string $text
     * @param array|null $reply_markup
     * @return object
     */
    function msg(string $text,array $reply_markup = null) : object
        {
            return \Bip\Telegram\Call::sendMessage(
                chat_id : peer(),
                text : $text,
                parse_mode : 'html',
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
            chat_id : peer(),
            text : $text,
            parse_mode : 'html',
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
        return \Bip\Telegram\Webhook::getObject();
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
        return \Bip\Telegram\Webhook::getObject()->message->from->id ?? \Bip\Telegram\Webhook::getObject()->callback_query->from->id ?? -1;
    }
}

if (! function_exists('text')){

    /**
     * get message text.
     * @return string
     */
    function text(): string
    {
        return \Bip\Telegram\Webhook::getObject()->message->text ?? '';
    }

}
if (! function_exists('data')){
    /**
     * get callback data.
     * @return string
     */
    function data(): string
    {
        return \Bip\Telegram\Webhook::getObject()->callback_query->data ?? '';
    }
}
if (! function_exists('emsg')){
    /**
     * send message with edit.
     * @param string $text
     * @param array|null $reply_markup
     * @return void
     */
    function emsg(string $text,array $reply_markup = null, $message_id = null): void
    {
        \Bip\Telegram\Call::editMessageText(
            text: $text,
            chat_id: peer(),
            message_id: $message_id ? : \Bip\Telegram\Webhook::getObject()->callback_query->message->message_id,
            parse_mode: 'html',
            reply_markup: $reply_markup
        );
    }
}
if (! function_exists('deleteMessage')){
    /**
     * delete message.
     * @param int $message_id
     * @return void
     */
    function deleteMessage(int $message_id): void
    {
        \Bip\Telegram\Call::deleteMessage(
            chat_id: peer(),
            message_id: $message_id
        );
    }
}
if (! function_exists('delLastMsg')){
    /**
     * delete last message.
     * @return void
     */
    function delLastMsg(): void
    {
        \Bip\Telegram\Call::deleteMessage(
            chat_id: peer(),
            message_id: \Bip\Telegram\Webhook::getObject()->message->message_id ?? \Bip\Telegram\Webhook::getObject()->callback_query->message->message_id
        );
    }
}
if (! function_exists('acq')){
    /**
     * answer callback query.
     * @param string $text
     * @param bool $show_alert
     * @param string $url
     * @param int $cache_time
     * @return void
     */
    function acq(string $text = '',bool $show_alert = false,string $url = '',int $cache_time = 0): void{
        \Bip\Telegram\Call::answerCallbackQuery(
            callback_query_id: \Bip\Telegram\Webhook::getObject()->callback_query->id,
            text: $text,
            show_alert: $show_alert,
            url: $url,
            cache_time: $cache_time
        );
    }
    
}
