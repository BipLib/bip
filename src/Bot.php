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
use Bip\Telegram\Update;
use Exception;

class Bot
{
    private static $bot = null;
    private object $stage;
    private Database $database;
    private string $newStage;
    private string $toBeRoutedNode;
    private string $routedNode;

    /**
     * initialize bot.
     * @param Stage $stage
     * @return Bot|null
     */
    public static function init(Stage $stage): ?Bot
    {
        if(empty(self::$bot))
            self::$bot = new Bot($stage);
        return self::$bot;
    }
    /**
     * Bot constructor.
     * @param Stage $stage
     * @throws Exception
     */
    private function __construct(Stage $stage)
    {
        $this->stage = $stage;
        $this->database = Config::get('database');

        Update::init(json_decode(file_get_contents('php://input')));

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
     * set a property to be added in stage.
     * @param string $name
     * @param mixed $value
     */
    public static function setProperty(string $name, mixed $value)
    {
        self::$bot->stage->{$name} = $value;
    }

    /**
     * unset a property.
     * @param string $name
     */
    public static function unsetProperty(string $name)
    {
        unset(self::$bot->stage->{$name});
    }

    /**
     * run the bot.
     */
    public static function run()
    {
        // call the stage controller
        self::$bot->stage->controller();

        // call the reserved node
        if(!empty(self::$bot->routedNode))
            self::$bot->stage->{self::$bot->routedNode . 'Node'}();
        elseif(!empty(self::$bot->stage->_node))
            self::$bot->stage->{self::$bot->stage->_node . 'Node'}();

        // change stage if $newStage be isn't empty.
        if (!empty(self::$bot->newStage)) {
            $newStage = self::$bot->newStage; //method call conflict.
            self::$bot->stage = new $newStage();
            unset($newStage);
        }

        // remove all non-primitive data types
        foreach (self::$bot->stage as $propertyKey => $propertyVal)
            if (is_object($propertyVal))
                unset(self::$bot->stage->{$propertyKey});

        // update stage in database
        self::$bot->database->updateStage(Update::get()->message->chat->id, self::$bot->stage);

    }

    /**
     * binds a node.
     * @param string $nodeName
     * @throws Exception
     */
    public static function bindNode(string $nodeName)
    {
        if(!method_exists(self::$bot->stage,$nodeName.'Node')) {
            $stageName = get_class(self::$bot->stage);
            throw new Exception("Bind Error : [$nodeName"."Node] method not found in [$stageName] stage");
        }

        self::$bot->stage->_node = $nodeName;
    }

    /**
     * changes the stage. (change will be applied when controller is finished in the current stage)
     * @param string $newStage
     */
    public static function changeStage(string $newStage)
    {
        self::$bot->newStage = $newStage;
    }

    /**
     * route to node.
     * @param string $nodeName
     * @return RouteRule
     * @throws Exception
     */
    public static function route(string $nodeName): RouteRule {
        if (!method_exists(self::$bot->stage, $nodeName . 'Node')) {
            $stageName = get_class(self::$bot->stage);
            throw new Exception("Route Error : [$nodeName" . "Node] method not found in [$stageName] stage");
        }

        self::$bot->toBeRoutedNode = $nodeName;
        return new RouteRule();
    }

    /**
     * get to be routed node. (last route $nodeName)
     * @return string
     */
    public static function getToBeRoutedNode(): string
    {
        return self::$bot->toBeRoutedNode;
    }

    /**
     * set routed node.
     * @param string $routedNode
     */
    public static function setRoutedNode(string $routedNode): void
    {
        self::$bot->routedNode = $routedNode;
    }

}