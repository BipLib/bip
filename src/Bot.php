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



use Bip\App\Stage;
use Bip\Database\Database;
use Exception;

class Bot
{
    private Stage $stage;
    private Database $database;

    /**
     * Bot constructor.
     * @param Stage $stage
     * @param Database $database
     */
    public function __construct(Stage $stage, Database $database)
    {
        $this->stage = $stage;
        $this->database = $database;

        if(!$this->database->insertUser($stage)){
            $stages = (array) $this->database->getStages();
            $stageName = array_key_last($stages);
            $this->stage = new $stageName();
            foreach ($stages[$stageName] as $propertyName => $propertyValue)
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
        $this->stage->controller($this);
        $this->database->updateStage($this->stage);

    }

    /**
     * start a Node.
     * @param string $nodeName
     */
    public function startNode(string $nodeName)
    {
        if(!isset($this->stage->__node__))
            $this->stage->__node__ = $nodeName;

        $this->stage->{$this->stage->__node__}();
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

        $this->stage->__node__ = $nodeName;
    }


}