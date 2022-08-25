<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip;


use Bip\App\Config;
use Bip\App\Stage;
use Bip\Database\Database;

class Bot
{
    private Config $config;
    public function __construct(Config $config)
    {
        $this->config = $config;
        // config validation 
    }

    public function setStage(Stage $stage)
    {
    }

    public function setDatabase(Database $database)
    {
    }

}