<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\Telegram\Update;


use Bip\Telegram\CallbackQuery;
use Bip\Telegram\ChatJoinRequest;
use Bip\Telegram\ChatMemberUpdated;
use Bip\Telegram\ChosenInlineResult;
use Bip\Telegram\InlineQuery;
use Bip\Telegram\Poll;
use Bip\Telegram\PollAnswer;
use Bip\Telegram\PreCheckoutQuery;
use Bip\Telegram\ShippingQuery;

/**
 * Class UpdateMapper
 * @package Bip\Telegram
 * @property int $update_id The update‘s unique identifier. Update identifiers start from a certain positive number and increase sequentially. This ID becomes especially handy if you’re using Webhooks, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
 * @property Message $message New incoming message of any kind — text, photo, sticker, etc.
 * @property Message $edited_message New version of a message that is known to the bot and was edited
 * @property Message $channel_post New incoming channel post of any kind — text, photo, sticker, etc.
 * @property Message $edited_channel_post New version of a channel post that is known to the bot and was edited
 * @property InlineQuery $inline_query New incoming inline query
 * @property ChosenInlineResult $chosen_inline_result The result of an inline query that was chosen by a user and sent to their chat partner. Please see our documentation on the feedback collecting for details on how to enable these updates for your bot.
 * @property CallbackQuery $callback_query New incoming callback query
 * @property ShippingQuery $shipping_query New incoming shipping query. Only for invoices with flexible price
 * @property PreCheckoutQuery $pre_checkout_query New incoming pre-checkout query. Contains full information about checkout
 * @property Poll $poll New poll state. Bots receive only updates about stopped polls and polls, which are sent by the bot
 * @property PollAnswer $poll_answer A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * @property ChatMemberUpdated $my_chat_member The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
 * @property ChatMemberUpdated $chat_member A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.
 * @property ChatJoinRequest $chat_join_request A request to join a chat has been sent. The bot must have can_invite_users administrator right in the chat to receive these updates.
 *
 *
 */
class Update extends Mapper
{
    protected array $objectsMap = [
        'update_id' => 'int',
        'message' => Message::class,
        'edited_message' => Message::class,
        'channel_post' => Message::class,
        'edited_channel_post' => Message::class,
        'inline_query' => InlineQuery::class,
        'chosen_inline_result' => ChosenInlineResult::class,
        'callback_query' => CallbackQuery::class,
        'shipping_query' => ShippingQuery::class,
        'pre_checkout_query' => PreCheckoutQuery::class,
        'poll' => Poll::class,
        'poll_answer' => PollAnswer::class,
        'my_chat_member' => ChatMemberUpdated::class,
        'chat_member' => ChatMemberUpdated::class,
        'chat_join_request' => ChatJoinRequest::class,
    ];

}