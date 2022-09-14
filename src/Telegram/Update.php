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


class Update
{
    private static object $update;

    public function __construct(object $update)
    {
        if (empty(self::$update))
            self::$update = $update;
    }

    /**
     * get update.
     * @return object
     */
    public static function get(): object
    {
        return self::$update;
    }

    /**
     * set update.
     * @param object $update
     * @return void
     */
    public static function set(object $update): void
    {
        self::$update = $update;
    }


}