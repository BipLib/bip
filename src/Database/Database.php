<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\Database;


use Bip\App\Stage;

interface Database
{
    /**
     * insert user. if the user exists, returns false, on success returns true.
     * @param int $chat_id
     * @param Stage $stage
     * @return bool
     */
    public function insertUser(int $chat_id, Stage $stage): bool;

    /**
     * get user. if the user does not exists, returns false, on success returns user data.
     * @param int $chat_id
     * @return array|bool
     */
    public function getUser(int $chat_id): array|bool;

    /**
     * update the user stage . returns true on success and false when user does not found.
     * @param int $chat_id
     * @param Stage $stage
     * @return bool
     */
    public function updateStage(int $chat_id, Stage $stage): bool;
}