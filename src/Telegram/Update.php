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


use Bip\Telegram\Update\Message;

/**
 * Class Update
 * @package Bip\Telegram
 * @property Message $message
 */
class Update
{
    private static Update $update;
    private object $object;

    /**
     * Update constructor.
     */
    private function __construct(){

    }

    /**
     * get update instance.
     * @return Update
     */
    public static function get(): object
    {
        self::init();
        return self::$update->object;
    }

    /**
     * initialize update.
     */
    public static function init(): void
    {
        if(empty(self::$update)) {
            self::$update = new Update();
            self::$update->object = json_decode(file_get_contents('php://input'));
        }
    }



}