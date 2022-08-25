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
    private Stage $stage;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $config->validate(['token']);

    }

    /**
     * set start stage, it will be run when user isn't in a stage.
     * @param Stage $stage
     */
    public function setStartStage(Stage $stage)
    {
        $this->stage = $stage;
    }

    public function setDatabase(Database $database)
    {
    }

    /**
     * run the bot.
     */
    public function run()
    {
        $this->stage->_config = $this->config;
        $this->stage->controller();
    }

}