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
     * @param Stage $stage
     * @return bool
     */
    public function insertUser(Stage $stage): bool;

    /**
     * get the user stages. returns false on failure.
     * @return object|bool
     */
    public function getStages(): object|bool;

    /**
     * update the stage. returns true on success and false on failure.
     * @param Stage $stage
     * @return bool
     */
    public function updateStage(Stage $stage): bool;
}