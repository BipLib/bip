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
use Bip\App\ConfigFactory;
use Bip\App\Stage;
use Bip\Database\Database;

class Bot
{
    private Config $config;
    private Stage $stage;
    private Database $database;

    public function __construct()
    {
        $config = ConfigFactory::get('bot');
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
        $this->database = $database;
    }

    /**
     * run the bot.
     */
    public function run()
    {
        $this->stage->_config = $this->config;
        if($this->database->getStage() == false) {
            $this->stage->controller();
            unset($this->stage->_config);
            $this->database->insertUser($this->stage);
        }else{
            $stage = $this->database->getStage();
            $stage->_config = $this->config;
            $stage->controller();
            unset($stage->_config);
            $this->database->updateStage($stage);
        }
    }

}