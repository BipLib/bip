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


use function file_get_contents;
use function json_decode;

class Update
{
    private static mixed $update;

    /**
     * constructor is private to prevent making instance of Update.
     */
    private function __construct()
    {
    }

    /**
     * initialize update.
     * @param null $associative
     */
    private static function init($associative = null)
    {
        if (empty(self::$update))
            self::$update = json_decode(file_get_contents('php://input'), $associative);
    }

    /**
     * get update as Array.
     * @return array|null
     */
    public static function asArray(): array|null
    {
        self::init(true);
        return (array)self::$update;
    }

    /**
     * get update as Object.
     * @return object|null
     */
    public static function asObject(): object|null
    {
        self::init();
        return self::$update;
    }

}