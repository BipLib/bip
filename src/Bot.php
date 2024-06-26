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
    public static string $stagePath; // for decreasing redundancy of database
    private static array $allowedUpdates = [];
    private static int $mode = 0;
    const MODE_DEV = 0;
    const MODE_PROD = 1;

    /**
     * run the bot. the passed stage must be in root of stages directory.
     */
    public static function run(Stage $stage): void
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

        // checking mode
        if(self::$mode == self::MODE_PROD) {
            //protect the bot from unauthorized access
            if (!(
                self::ipInRange($_SERVER['REMOTE_ADDR'], '91.108.4.0/22') // telegram ip range
                or self::ipInRange($_SERVER['REMOTE_ADDR'], '149.154.160.0/20') // telegram ip range
                or self::ipInRange($_SERVER['REMOTE_ADDR'], '127.0.0.0/8') // local ip range system loop back
                or self::ipInRange($_SERVER['REMOTE_ADDR'], '10.0.0.0/8') // local ip range class A
                or self::ipInRange($_SERVER['REMOTE_ADDR'], '172.16.0.0/12') // local ip range class B
                or self::ipInRange($_SERVER['REMOTE_ADDR'], '192.168.0.0/16') // local ip range class C
            ))
                die('Unauthorized IP Address !');

        }

        // checking allowed updates
        if(!empty(self::$allowedUpdates)){
            $boolResult = false;
            foreach (self::$allowedUpdates as $allowedUpdate)
                if(isset(Webhook::getObject()->{$allowedUpdate}))
                    $boolResult = true;
            if (!$boolResult)
                die('Unauthorized Update !');
        }


        // create bot instance
        if(empty(self::$bot)) {
            self::$bot = new Bot();
            self::$bot->stage = $stage;
            self::$bot->database = Config::get('database')['driver']::init(...Config::get('database')['args']);

            // remove last part of namespace for getting stage path
            self::$stagePath = substr(get_class(self::$bot->stage), 0, strrpos(get_class(self::$bot->stage), '\\')).'\\';

            if(peer() == -1)
                die("Failed To Inserting User !");

            if (!self::$bot->database->insertUser(peer(), self::$bot->stage)) {

                //convert stdClass object to Stage object
                $user = self::$bot->database->getUser(peer());
                $call = $user['stage_call'];
                $call = Bot::$stagePath.$call;
                self::$bot->stage = new $call();
                foreach ($user['stages'][$user['stage_call']] as $propertyName => $propertyValue)
                    if(property_exists(self::$bot->stage,$propertyName))
                        self::$bot->stage->{$propertyName} = $propertyValue;
                    else
                        Logger::add('Removing Unset Property : the property ['.$propertyName.' with value of :  '.var_export($propertyValue,true).'] in stage '.self::$bot->stage::class. ' not exist. it be removed form database. user :'.peer(),'stage');

            }

        }

        // smart callback_data
        if (str_starts_with(data(),'@')){
            $smartCallback = explode('#',data());
            route(str_replace('@', '', $smartCallback[0]));
        }

        // call the stage controller
        self::$bot->stage->controller();

        // call the reserved node
        if(!empty(self::$bot->routedNode)) {
            if (isset($smartCallback) && count($smartCallback)>1)
                // pass all $smartCallback data members expect index zero
                self::$bot->stage->{self::$bot->routedNode}(...array_splice($smartCallback, 1,count($smartCallback)-1));
            else
                self::$bot->stage->{self::$bot->routedNode}();


        }
        elseif(!empty(self::$bot->stage->_node))
            self::$bot->stage->{self::$bot->stage->_node }();
        elseif (empty(self::$bot->stage->_node) or self::$bot->stage->_node == 'default')
            self::$bot->stage->default();



        // remove all non-primitive data types
        foreach (self::$bot->stage as $propertyKey => $propertyVal)
            if (is_object($propertyVal))
                unset(self::$bot->stage->{$propertyKey});

        // update stage in database
        self::$bot->database->updateStage(peer(), self::$bot->stage);


        // change stage if $newStage be isn't empty.
        if (!empty(self::$bot->newStage)) {
            $newStage = self::$bot->newStage;
            self::$bot->stage = new $newStage();
            // update again for changing stage
            self::$bot->database->updateStage(peer(), self::$bot->stage);
        }
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
     * binds a node.
     * @param string $nodeName
     * @throws Exception
     */
    public static function bindNode(string $nodeName): void
    {
        if(!method_exists(self::$bot->stage,$nodeName)) {
            $stageName = get_class(self::$bot->stage);
            throw new Exception("Bind Error : [$nodeName] Node not found in [$stageName] stage");
        }

        self::$bot->stage->_node = $nodeName;
    }

    /**
     * close the current node.(it will bind to `default` node, `default` node if not exists, it will be ignored)
     */
    public static function closeNode(): void
    {
        bindNode('default');
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
     * check if the ip is in range.
     * @param $ip
     * @param $range
     * @return bool
     */
    private static function ipInRange($ip,$range): bool
    {
        if(!str_contains($range, '/'))
            $range .= '/32';
        list($range, $netmask) = explode('/',$range,2);
        $range_decimal = ip2long($range);
        $ip_decimal = ip2long($ip);
        $wildcard_decimal = pow(2,(32-$netmask))-1;
        $netmask_decimal = ~ $wildcard_decimal;

        return ($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal);

    }
    /**
     * set bot mode.
     * @param int $mode
     */
    public static function setMode(int $mode): void
    {
        self::$mode = $mode;
    }

    /**
     * set allowed ip addresses.
     * @param array $allowedUpdates
     */
    public static function setAllowedUpdates(array $allowedUpdates): void
    {
        self::$allowedUpdates = $allowedUpdates;
    }


}