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
use Bip\App\RouteRule;
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
    private string $toBeRoutedNode;
    private string $routedNode;



    /**
     * Bot constructor.
     * @param Stage $stage
     * @param Database $database
     * @param Telegram $telegram
     * @param Config $config
     * @throws Exception
     */
    public function __construct(Stage $stage, Database $database, Telegram $telegram, Config $config)
    {
        $this->stage = $stage;
        $this->database = $database;
        $this->telegram = $telegram;
        $this->config = $config;

        $config->validate(['token']);
        $telegram->setToken($config->get('token'));

        if (!$this->database->insertUser(Update::get()->message->chat->id, $this->stage)) {
            //convert stdClass object to Stage object
            $stageStdClass = $this->database->getStage(Update::get()->message->chat->id);
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
    public function setProperty(string $name, mixed $value)
    {
        $this->stage->{$name} = $value;
    }

    /**
     * unset a property.
     * @param string $name
     */
    public function unsetProperty(string $name)
    {
        unset($this->stage->{$name});
    }

    /**
     * run the bot.
     */
    public function run()
    {
        // call the stage controller
        $this->stage->controller($this);

        // call the reserved node
        if(!empty($this->routedNode))
            $this->stage->{$this->routedNode . 'Node'}();
        elseif(!empty($this->stage->_node))
            $this->stage->{$this->stage->_node . 'Node'}();

        // change stage if $newStage be isn't empty.
        if (!empty($this->newStage)) {
            $newStage = $this->newStage; //method call conflict.
            $this->stage = new $newStage();
            unset($newStage);
        }

        // remove all non-primitive data types
        foreach ($this->stage as $propertyKey => $propertyVal)
            if (is_object($propertyVal))
                unset($this->stage->{$propertyKey});

        // update stage in database
        $this->database->updateStage(Update::get()->message->chat->id, $this->stage);

    }

    /**
     * binds a node.
     * @param string $nodeName
     * @throws Exception
     */
    public function bindNode(string $nodeName)
    {
        if(!method_exists($this->stage,$nodeName.'Node')) {
            $stageName = get_class($this->stage);
            throw new Exception("Bind Error : [$nodeName"."Node] method not found in [$stageName] stage");
        }

        $this->stage->_node = $nodeName;
    }

    /**
     * changes the stage. (change will be applied when controller is finished in the current stage)
     * @param string $newStage
     */
    public function changeStage(string $newStage)
    {
        $this->newStage = $newStage;
    }

    /**
     * route to node.
     * @param string $nodeName
     * @return RouteRule
     * @throws Exception
     */
    public function route(string $nodeName): RouteRule {
        if (!method_exists($this->stage, $nodeName . 'Node')) {
            $stageName = get_class($this->stage);
            throw new Exception("Route Error : [$nodeName" . "Node] method not found in [$stageName] stage");
        }

        $this->toBeRoutedNode = $nodeName;
        return new RouteRule($this);
    }

    /**
     * get to be routed node. (last route $nodeName)
     * @return string
     */
    public function getToBeRoutedNode(): string
    {
        return $this->toBeRoutedNode;
    }

    /**
     * set routed node.
     * @param string $routedNode
     */
    public function setRoutedNode(string $routedNode): void
    {
        $this->routedNode = $routedNode;
    }

    /**
     * get Telegram driver.
     * @return Telegram
     */
    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }



}