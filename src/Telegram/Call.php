<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\Telegram;


use Bip\App\Config;
use Bip\Logger\Logger;

/**
 * Class Telegram
 * @package Bip\Telegram
 *
 * All Telegram Methods (Need To Update This List)
 *
 * @method static object getMe() A simple method for testing your bot's auth token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static object sendMessage(int $chat_id, string $text, string $parse_mode = null, bool $disable_web_page_preview = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send text messages. On success, the sent Message is returned.
 * @method static object forwardMessage(int $chat_id, int $from_chat_id, int $message_id, bool $disable_notification = null) Use this method to forward messages of any kind. On success, the sent Message is returned.
 * @method static object sendPhoto(int $chat_id, string $photo, string $caption = null, string $parse_mode = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send photos. On success, the sent Message is returned.
 * @method static object sendAudio(int $chat_id, string $audio, string $caption = null, string $parse_mode = null, int $duration = null, string $performer = null, string $title = null, string $thumb = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .mp3 format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.
 * @method static object sendDocument(int $chat_id, object|string $document, string $thumb = null, string $caption = null, string $parse_mode = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static object sendVideo(int $chat_id, string $video, int $duration = null, int $width = null, int $height = null, string $thumb = null, string $caption = null, string $parse_mode = null, bool $supports_streaming = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send video files, Telegram clients support mp4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static object sendAnimation(int $chat_id, string $animation, int $duration = null, int $width = null, int $height = null, string $thumb = null, string $caption = null, string $parse_mode = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static object sendVoice(int $chat_id, string $voice, string $caption = null, string $parse_mode = null, int $duration = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .ogg file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static object sendVideoNote(int $chat_id, string $video_note, int $duration = null, int $length = null, string $thumb = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) As of v.4.0, Telegram clients support rounded square mp4 videos of up to 1 minute long. Use this method to send video messages. On success, the sent Message is returned.
 * @method static object sendMediaGroup(int $chat_id, array $media, bool $disable_notification = null, int $reply_to_message_id = null) Use this method to send a group of photos or videos as an album. On success, an array of the sent Messages is returned.
 * @method static object sendLocation(int $chat_id, float $latitude, float $longitude, int $live_period = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send point on the map. On success, the sent Message is returned.
 * @method static object editMessageLiveLocation(float $latitude, float $longitude, int $chat_id = null, int $message_id = null, string $inline_message_id = null, array $reply_markup = null) Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message was sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static object stopMessageLiveLocation(int $chat_id = null, int $message_id = null, string $inline_message_id = null, array $reply_markup = null) Use this method to stop updating a live location message before live_period expires. On success, if the message was sent by the bot, the sent Message is returned, otherwise True is returned.
 * @method static object sendVenue(int $chat_id, float $latitude, float $longitude, string $title, string $address, string $foursquare_id = null, string $foursquare_type = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static object sendContact(int $chat_id, string $phone_number, string $first_name, string $last_name = null, string $vcard = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static object sendPoll(int $chat_id, string $question, array $options, bool $is_anonymous = null, string $type = null, bool $allows_multiple_answers = null, int $correct_option_id = null, string $explanation = null, string $explanation_parse_mode = null, int $open_period = null, int $close_date = null, bool $is_closed = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send a native poll. On success, the sent Message is returned.
 * @method static object sendDice(int $chat_id, string $emoji = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send an animated emoji that will display a random value. On success, the sent Message is returned.
 * @method static object sendChatAction(int $chat_id, string $action) Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.
 * @method static object getUserProfilePhotos(int $user_id, int $offset = null, int $limit = null) Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static object getFile(string $file_id) Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static object banChatMember(int $chat_id, int $user_id, int $until_date = null, bool $revoke_messages = null) Use this method to ban a user in a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the group on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object unbanChatMember(int $chat_id, int $user_id, bool $only_if_banned = null) Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. Returns True on success.
 * @method static object restrictChatMember(int $chat_id, int $user_id, int $until_date = null, bool $can_send_messages = null, bool $can_send_media_messages = null, bool $can_send_other_messages = null, bool $can_add_web_page_previews = null) Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate admin rights. Pass True for all boolean parameters to lift restrictions from a user. Returns True on success.
 * @method static object promoteChatMember(int $chat_id, int $user_id, bool $can_change_info = null, bool $can_post_messages = null, bool $can_edit_messages = null, bool $can_delete_messages = null, bool $can_invite_users = null, bool $can_restrict_members = null, bool $can_pin_messages = null, bool $can_promote_members = null) Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static object setChatAdministratorCustomTitle(int $chat_id, int $user_id, string $custom_title) Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 * @method static object setChatPermissions(int $chat_id, object $permissions) Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members admin rights. Returns True on success.
 * @method static object exportChatInviteLink(int $chat_id) Use this method to generate a new invite link for a chat; any previously generated link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns the new invite link as String on success.
 * @method static object setChatPhoto(int $chat_id, string $photo) Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object deleteChatPhoto(int $chat_id) Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object setChatTitle(int $chat_id, string $title) Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object setChatDescription(int $chat_id, string $description = null) Use this method to change the description of a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object pinChatMessage(int $chat_id, int $message_id, bool $disable_notification = null) Use this method to pin a message in a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object unpinChatMessage(int $chat_id , int $message_id) Use this method to unpin a message in a supergroup chat. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static object leaveChat(int $chat_id) Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static object getChat(int $chat_id) Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static object getChatAdministrators(int $chat_id) Use this method to get a list of administrators in a chat. On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
 * @method static object getChatMembersCount(int $chat_id) Use this method to get the number of members in a chat. Returns Int on success.
 * @method static object getChatMember(int $chat_id, int $user_id) Use this method to get information about a member of a chat. Returns a ChatMember object on success.
 * @method static object setChatStickerSet(int $chat_id, string $sticker_set_name) Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static object deleteChatStickerSet(int $chat_id) Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static object answerCallbackQuery(string $callback_query_id, string $text = null, bool $show_alert = null, string $url = null, int $cache_time = null) Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static object editMessageText(string $text, int $chat_id = null, int $message_id = null, string $inline_message_id = null, string $parse_mode = null, bool $disable_web_page_preview = null, array $reply_markup = null) Use this method to edit text and game messages. On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static object editMessageCaption(string $caption, int $chat_id = null, int $message_id = null, string $inline_message_id = null, array $reply_markup = null) Use this method to edit captions of messages. On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static object editMessageReplyMarkup(int $chat_id = null, int $message_id = null, string $inline_message_id = null, array $reply_markup = null) Use this method to edit only the reply markup of messages. On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static object stopPoll(int $chat_id, int $message_id, array $reply_markup = null) Use this method to stop a poll which was sent by the bot. On success, the stopped Poll with the final results is returned.
 * @method static object deleteMessage(int $chat_id, int $message_id) Use this method to delete a message, including service messages, with the following limitations: - A message can only be deleted if it was sent less than 48 hours ago. - Bots can delete outgoing messages in private chats, groups, and supergroups. - Bots can delete incoming messages in private chats. - Bots granted can_post_messages permissions can delete outgoing messages in channels. - If the bot is an administrator of a group, it can delete any message there. - If the bot has can_delete_messages permission in a supergroup or a channel, it can delete any message there. Returns True on success.
 * @method static object sendSticker(int $chat_id, string $sticker, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send static .WEBP or animated .TGS stickers. On success, the sent Message is returned.
 * @method static object getStickerSet(string $name) Use this method to get a sticker set. On success, a StickerSet object is returned.
 * @method static object uploadStickerFile(int $user_id, object $png_sticker) Use this method to upload a .PNG file with a sticker for later use in createNewStickerSet and addStickerToSet methods (can be used multiple times). Returns the uploaded File on success.
 * @method static object createNewStickerSet(int $user_id, string $name, string $title, object $png_sticker, string $emojis, bool $contains_masks = null, object $mask_position = null) Use this method to create new sticker set owned by a user. The bot will be able to edit the created sticker set. Returns True on success.
 * @method static object addStickerToSet(int $user_id, string $name, object $png_sticker, string $emojis, object $mask_position = null) Use this method to add a new sticker to a set created by the bot. Returns True on success.
 * @method static object setStickerPositionInSet(string $sticker, int $position) Use this method to move a sticker in a set created by the bot to a specific position . Returns True on success.
 * @method static object deleteStickerFromSet(string $sticker) Use this method to delete a sticker from a set created by the bot. Returns True on success.
 * @method static object setStickerSetThumb(string $name, int $user_id, object $thumb = null) Use this method to set the thumbnail of a sticker set. Animated thumbnails can be set for animated sticker sets only. Returns True on success.
 * @method static object answerInlineQuery(string $inline_query_id, object $results, int $cache_time = null, bool $is_personal = null, string $next_offset = null, string $switch_pm_text = null, string $switch_pm_parameter = null) Use this method to send answers to an inline query. On success, True is returned. No more than 50 results per query are allowed.
 * @method static object sendInvoice(int $chat_id, string $title, string $description, string $payload, string $provider_token, string $start_parameter, string $currency, array $prices, string $provider_data = null, string $photo_url = null, int $photo_size = null, int $photo_width = null, int $photo_height = null, bool $need_name = null, bool $need_phone_number = null, bool $need_email = null, bool $need_shipping_address = null, bool $send_phone_number_to_provider = null, bool $send_email_to_provider = null, bool $is_flexible = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send invoices. On success, the sent Message is returned.
 * @method static object answerShippingQuery(string $shipping_query_id, object $ok, object $shipping_options = null, string $error_message = null) If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 * @method static object answerPreCheckoutQuery(string $pre_checkout_query_id, object $ok, string $error_message = null) Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned. Note: The Bot API must receive an answer within 10 seconds after the pre-checkout query was sent.
 * @method static object setPassportDataErrors(int $user_id, object $errors) Use this method to notify the user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success.
 * @method static object sendGame(int $chat_id, string $game_short_name, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to send a game. On success, the sent Message is returned.
 * @method static object setGameScore(int $user_id, int $score, bool $force = null, bool $disable_edit_message = null, int $chat_id = null, int $message_id = null, string $inline_message_id = null) Use this method to set the score of the specified user in a game. On success, if the message was sent by the bot, returns the edited Message, otherwise returns True. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 * @method static object getGameHighScores(int $user_id, int $chat_id = null, int $message_id = null, string $inline_message_id = null) Use this method to get data for high score tables. Will return the score of the specified user and several of his neighbors in a game. On success, returns an Array of GameHighScore objects.
 * @method static object deleteWebhook(string $drop_pending_updates = null) Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success. Requires no parameters.
 * @method static object setWebhook(string $url, object $certificate = null, int $max_connections = null, array $allowed_updates = null , bool $drop_pending_updates = false) Use this method to specify a url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns True on success. If you'd like to make sure that the Webhook request comes from Telegram, we recommend using a secret path in the URL, e.g. https://www.example.com/<token>. Since nobody else knows your bot's token, you can be pretty sure it's us.
 * @method static object getWebhookInfo() Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static object logOut() Use this method to log out from the cloud Bot API server before launching the bot locally. You must log out the bot before running it locally, otherwise there is no guarantee that the bot will receive updates. After a successful call, you can immediately log in on a local server, but will not be able to log in back to the cloud Bot API server for 10 minutes. Returns True on success. Requires no parameters.
 * @method static object close() Use this method to close the bot instance before moving it from one local server to another. You need to delete the webhook before calling this method to ensure that the bot isn't launched again after server restart. The method will return error 429 in the first 10 minutes after the bot is launched. Returns True on success. Requires no parameters.
 * @method static object copyMessage(int $chat_id, int $from_chat_id, int $message_id, string $caption = null, string $parse_mode = null, bool $disable_notification = null, int $reply_to_message_id = null, array $reply_markup = null) Use this method to copy messages of any kind. The method is analogous to the method forwardMessage, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
 */

class Call
{
    /**
     * Call telegram methods.
     * @param string $method
     * @param array $params
     * @return object
     */
    public static function api(string $method, array $params = []): object
    {
        $ch = curl_init((Config::isSet('local_server') ? Config::get('local_server') : 'https://api.telegram.org/bot' ). Config::get('token') . '/' . $method);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        foreach ($params as $paramKey => $paramValue) {
            if($paramKey == 'reply_markup')
                if(isset($paramValue['inline_keyboard']))
                    foreach ($paramValue['inline_keyboard'] as $key => $value)
                        foreach ($value as $key2 => $value2)
                            if(isset($value2['callback_data'])){
                                $encrypt = openssl_encrypt(
                                    data: $value2['callback_data'],
                                    cipher_algo:'AES-256-CTR',
                                    passphrase: md5(Config::get('token')),
                                    iv: substr(md5(Config::get('token')),0,16)); // each 3 days iv will change and old callback data will not work
                                if(strlen($encrypt) > 64)
                                    throw new \Exception('Callback data is too long. Crypt of callback data must be less than 64 characters. ['.$value2['callback_data'].'] -> ['.$encrypt.'] len : '.strlen($encrypt));
                                $paramValue['inline_keyboard'][$key][$key2]['callback_data'] =  $encrypt;
                            }






            if (is_array($paramValue))
                $params[$paramKey] = json_encode($paramValue);

        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch,CURLOPT_TIMEOUT,10);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        $response = curl_exec($ch);
        if($response)
            return json_decode($response);
        else
            throw new \Exception("Couldn't connect to telegram api");

    }

    public static function __callStatic(string $name, array $arguments)
    {
        return self::api($name, $arguments);
    }


}