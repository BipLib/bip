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
use Bip\Logger\Logger;
use Bip\Telegram\Webhook;
use ErrorException;
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
        //setting error and exception handler
        set_exception_handler(function ($exception) {
            $message = [];
            $message['ip'] = $_SERVER['REMOTE_ADDR'] ?? 'no ip';
            $message['update'] = Webhook::getObject() ?? 'no update';
            $message['level'] = $exception->getCode();
            $message['message'] = $exception->getMessage();
            $message['file'] = $exception->getFile();
            $message['line'] = $exception->getLine();
            Logger::add($message,'errors');
            echo 'Something is Wrong! Please try again later.';
        });
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }, E_ALL);


        // create bot instance
        if(empty(self::$bot)) {
        self::$bot = new Bot();
        self::$bot->stage = $stage;
        self::$bot->database = Config::get('database');

        if (!self::$bot->database->insertUser(Webhook::getObject()->message->chat->id, self::$bot->stage)) {

            //convert stdClass object to Stage object
            $user = self::$bot->database->getUser(Webhook::getObject()->message->chat->id);
            $call = $user['stage_call'];
            self::$bot->stage = new $call();
            foreach ($user['stages'][$user['stage_call']] as $propertyName => $propertyValue)
                self::$bot->stage->{$propertyName} = $propertyValue;

        }

    }
        return self::$bot;

    }

    /**
     * Bot constructor.
     */
    private function __construct(){}

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
            self::$bot->stage->{self::$bot->routedNode }();
        elseif(!empty(self::$bot->stage->_node))
            self::$bot->stage->{self::$bot->stage->_node }();
        elseif (empty(self::$bot->stage->_node) or self::$bot->stage->_node == 'default')
            self::$bot->stage->default();


        // remove all non-primitive data types
        foreach (self::$bot->stage as $propertyKey => $propertyVal)
            if (is_object($propertyVal))
                unset(self::$bot->stage->{$propertyKey});

        // update stage in database
        self::$bot->database->updateStage(Webhook::getObject()->message->chat->id, self::$bot->stage);


        // change stage if $newStage be isn't empty.
        if (!empty(self::$bot->newStage)) {
            $newStage = self::$bot->newStage;
            self::$bot->stage = new $newStage();

            // update again for changing stage
            self::$bot->database->updateStage(Webhook::getObject()->message->chat->id, self::$bot->stage);

        }
    }

    /**
     * binds a node.
     * @param string $nodeName
     * @throws Exception
     */
    public static function bindNode(string $nodeName)
    {
        if(!method_exists(self::$bot->stage,$nodeName)) {
            $stageName = get_class(self::$bot->stage);
            throw new Exception("Bind Error : [$nodeName] Node not found in [$stageName] stage");
        }


        // it means `route` doesn't called before `bindNode`.
        // when you calling `route` method, it will set the _node.
        if(empty(self::$bot->toBeRoutedNode))
            self::$bot->stage->_prev = self::$bot->stage->_node;
        // in `route` bound instead of here ...

        self::$bot->stage->_node = $nodeName;


    }
    /**
     * close the current node.(it will bind to `default` node, `default` node if not exists, it will be ignored)
     */
    public static function closeNode(): void
    {
        self::$bot->stage->_prev = self::$bot->stage->_node;
        self::$bot->stage->_node = 'default';
    }


    /**
     * changes the stage. (change will be applied when controller is finished in the current stage)
     * @param Stage $newStage
     */
    public static function changeStage(Stage $newStage): void
    {
        self::$bot->newStage = $newStage::class;
    }

    /**
     * route to node.
     * @param string $nodeName
     * @return RouteRule
     * @throws Exception
     */
    public static function route(string $nodeName): RouteRule {
        if (!method_exists(self::$bot->stage, $nodeName )) {
            $stageName = get_class(self::$bot->stage);
            throw new Exception("Route Error : [$nodeName] Node not found in [$stageName] stage");
        }

        self::$bot->stage->_prev = $nodeName;
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

    /**
     * get the previous node.
     * @return string
     */
    public static function getPreviousNode(): string
    {
        return self::$bot->stage->_prev;

    }


}