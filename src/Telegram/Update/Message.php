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


/**
 * Class Message - Bot API 6.5
 * @package Bip\Telegram\Update
 *
 * @property int $message_id Unique message identifier inside this chat
 * @property int $message_thread_id Unique identifier of a message thread to which the message belongs; for supergroups only
 * @property User $from  Sender of the message; empty for messages sent to channels. For backward compatibility, the field contains a fake sender user in non-channel chats, if the message was sent on behalf of a chat.
 * @property User $sender_chat Sender of the message, sent on behalf of a chat. The channel itself for channel messages. The supergroup itself for messages from anonymous group administrators. The linked channel for messages automatically forwarded to the discussion group. For backward compatibility, in non-channel chats, the field contains a fake sender user, if the message was sent on behalf of a chat.
 * @property int $date Date the message was sent in Unix time
 * @property Chat $chat Conversation the message belongs to
 * @property User $forward_from For forwarded messages, sender of the original message
 * @property Chat $forward_from_chat For messages forwarded from a channel, information about the original channel
 * @property int $forward_from_message_id For messages forwarded from a channel, identifier of the original message in the channel
 * @property string $forward_signature For messages forwarded from a channel, signature of the post author if present
 * @property string $forward_sender_name For messages forwarded from a channel, sender's name for messages forwarded from users who disallow adding a link to their account in forwarded messages
 * @property int $forward_date For forwarded messages, date the original message was sent in Unix time
 * @property true $is_topic_message True, if the message is sent to a forum topic
 * @property true $is_automatic_forward True, if the message is a channel post that was automatically forwarded to the connected discussion group
 * @property Message $reply_to_message For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @property User $via_bot Bot through which the message was sent
 * @property int $edit_date Date the message was last edited in Unix time
 * @property true $has_protected_content True, if the message can't be forwarded
 * @property string $media_group_id The unique identifier of a media message group this message belongs to
 * @property string $author_signature Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
 * @property string $text For text messages, the actual UTF-8 text of the message, 0-4096 characters.
 * @property MessageEntity[] $entities For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @property Animation $animation Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set
 * @property Audio $audio Message is an audio file, information about the file
 * @property Document $document Message is a general file, information about the file
 * @property PhotoSize[] $photo Message is a photo, available sizes of the photo
 * @property Sticker $sticker Message is a sticker, information about the sticker
 * @property Video $video Message is a video, information about the video
 * @property VideoNote $video_note Message is a video note, information about the video message
 * @property Voice $voice Message is a voice message, information about the file
 * @property string $caption Caption for the animation, audio, document, photo, video or voice, 0-1024 characters
 * @property MessageEntity[] $caption_entities For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @property Contact $contact Message is a shared contact, information about the contact
 * @property Dice $dice Message is a dice with random value
 * @property Game $game Message is a game, information about the game.
 * @property Poll $poll Message is a native poll, information about the poll
 * @property Venue $venue Message is a venue, information about the venue. For backward compatibility, when this field is set, the location field will also be set
 * @property Location $location Message is a shared location, information about the location
 * @property User[] $new_chat_members New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
 * @property User $left_chat_member A member was removed from the group, information about them (this member may be the bot itself)
 * @property string $new_chat_title A chat title was changed to this value
 * @property PhotoSize[] $new_chat_photo A chat photo was change to this value
 * @property true $delete_chat_photo Service message: the chat photo was deleted
 * @property true $group_chat_created Service message: the group has been created
 * @property true $supergroup_chat_created Service message: the supergroup has been created. This field can‘t be received in a message coming through updates, because bot can’t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @property true $channel_chat_created Service message: the channel has been created. This field can‘t be received in a message coming through updates, because bot can’t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @property MessageAutoDeleteTimerChanged $message_auto_delete_timer_changed Service message: auto-delete timer settings changed in the chat
 * @property int $migrate_to_chat_id The group has been migrated to a supergroup with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @property int $migrate_from_chat_id The supergroup has been migrated from a group with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @property Message $pinned_message Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it is itself a reply.
 * @property Invoice $invoice Message is an invoice for a payment, information about the invoice.
 * @property SuccessfulPayment $successful_payment Message is a service message about a successful payment, information about the payment.
 * @property UserShared $user_shared Service message: a user was shared with the bot
 * @property UserShared $chat_shared Service message: a chat was shared with the bot
 * @property string $connected_website The domain name of the website on which the user has logged in.
 * @property WriteAccessAllowed $write_access_allowed Service message: the user allowed the bot added to the attachment menu to write messages
 * @property PassportData $passport_data Telegram Passport data
 * @property ProximityAlertTriggered $proximity_alert_triggered Service message: a user in the chat triggered another user's proximity alert while sharing Live Location.
 * @property FroumTopicCreated $froum_topic_created Service message: forum topic created
 * @property FroumTopicEdited $froum_topic_edited Service message: forum topic edited
 * @property FroumTopicClosed $froum_topic_closed Service message: forum topic closed
 * @property FroumTopicReopened $froum_topic_reopened Service message: forum topic reopened
 * @property GeneralForumTopicHidden $general_forum_topic_hidden Service message: the 'General' forum topic hidden
 * @property GeneralForumTopicUnhidden $general_forum_topic_unhidden Service message: the 'General' forum topic unhidden
 * @property VideoChatScheduled $video_chat_scheduled Service message: video chat scheduled
 * @property VideoChatStarted $video_chat_started Service message: video chat started
 * @property VideoChatEnded $video_chat_ended Service message: video chat ended
 * @property VideoChatParticipantsInvited $video_chat_participants_invited Service message: new participants invited to a video chat
 * @property WebAppData $web_app_data Service message: data sent by a Web App
 * @property InlineKeyboardMarkup $reply_markup Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons.
 *
 */
class Message extends Mapper
{
    // TODO: add all fields
    // Important fields are already added
    protected array $objectsMap = [
        'message_id' =>'int',
        'message_thread_id' =>'int',
        'from' => User::class,
        'sender_chat' => Chat::class,
        'date' =>'int',
        'chat' => Chat::class,
        'forward_from' => User::class,
        'forward_from_chat' => Chat::class,
        'forward_from_message_id' =>'int',
        'forward_signature' =>'string',
        'forward_sender_name' =>'string',
        'forward_date' =>'int',
        'reply_to_message' => self::class,
        'text' =>'string',
        'entities' => MessageEntity::class,


    ];


}