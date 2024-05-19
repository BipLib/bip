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
use Bip\Telegram\Update\Message;
use Bip\Telegram\Update\Update;

/**
 * Class Update
 * @package Bip\Telegram
 * @property Message $message
 */
class Webhook
{
    private static Webhook $update;
    private ?object $object = null;

    /**
     * Update constructor.
     */
    private function __construct(){

    }

    /**
     * get update instance.
     * @return object|null
     */
    public static function getObject(): ?object
    {
        self::init();
        return self::$update->object;
    }
    public static function get(): Update
    {
       return new Update(self::getObject());
    }

    /**
     * initialize update.
     */
    public static function init(): void
    {
        if(empty(self::$update)) {
            self::$update = new Webhook();
            self::$update->object = json_decode(file_get_contents('php://input'));


            // decrypting callback_data of keyboards that encrypted with following algorithm:
            if(isset(self::$update->object->callback_query)) {

                $decrypt = openssl_decrypt(
                    data: self::$update->object->callback_query->data,
                    cipher_algo: 'AES-256-CTR',
                    passphrase: md5(Config::get('token')),
                    iv: substr(md5(Config::get('token')), 0, 16)
                );
                self::$update->object->callback_query->data = $decrypt;
            }
        }
    }






}