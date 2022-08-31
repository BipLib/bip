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
use Bip\Telegram\Telegram;
use Bip\Telegram\Update;
use Exception;

class Bot
{
    private object $stage;
    private Database $database;
    private Telegram $telegram;
    private Config $config;
    private string $newStage;
    /**
     * Bot constructor.
     * @param Stage $stage
     * @param Database $database
     * @param Telegram $telegram
     * @param Config $config
     * @throws Exception
     */
    public function __construct(Stage $stage, Database $database,Telegram $telegram,Config $config)
    {
        $this->stage    = $stage;
        $this->database = $database;
        $this->telegram = $telegram;
        $this->config   = $config;

        $config->validate(['token']);
        $telegram->setToken($config->get('token'));

        if(!$this->database->insertUser(Update::asObject()->message->chat->id,$this->stage)){
            //convert stdClass object to Stage object
            $stageStdClass =  $this->database->getStage(Update::asObject()->message->chat->id);
            $call = $stageStdClass->_call;
            $this->stage = new $call();
            foreach ($stageStdClass as $propertyName => $propertyValue)
                    $this->stage->{$propertyName} = $propertyValue;

        }
    }

    /**
     * set a property to be add in stage.
     * @param string $name
     * @param mixed $value
     */
    public function setProperty(string $name, mixed $value){
        $this->stage->{$name} = $value;
    }

    /**
     * unset a property.
     * @param string $name
     */
    public function unsetProperty(string $name){
        unset($this->stage->{$name});
    }
    /**
     * run the bot.
     */
    public function run()
    {
        $this->stage->controller($this,$this->telegram);

        if(!empty($this->newStage)) {
            $newStage = $this->newStage; //method call conflict.
            $this->stage = new $newStage();
            unset($newStage);
        }

        $this->database->updateStage(Update::asObject()->message->chat->id,$this->stage);

    }

    /**
     * start a Node.
     * @param string $nodeName
     */
    public function startNode(string $nodeName)
    {
        if(empty($this->stage->_node))
            $this->stage->_node = $nodeName;

        $this->stage->{$this->stage->_node}();
    }

    /**
     * bind a node.
     * @param string $nodeName
     * @throws Exception
     */
    public function bindNode(string $nodeName)
    {
        if(!method_exists($this->stage,$nodeName)) {
            $stageName = $this->stage::class;
            throw new Exception("Bind Error : [$nodeName] node not found in [$stageName]");
        }

        $this->stage->_node = $nodeName;
    }

    /**
     * change the stage. (change will be applied when controller is finished in the current stage)
     * @param string $newStage
     */
    public function changeStage(string $newStage){
        $this->newStage = $newStage;
    }


}