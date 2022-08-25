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
    public function insert(Stage $stage) : bool;
    public function getStage() : Stage;
    public function updateStage(Stage $stage) : bool;
}