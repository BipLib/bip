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
use Bip\Telegram\Update;

/**
 * Class LazyJsonDatabase , [This database is slow and unsafe. it is designed to help the development, so it should not be used in other cases]
 * @package Bip\Database
 */
class LazyJsonDatabase implements Database
{
    private mixed $json;
    private string $file;
    /**
     * LazyJsonDatabase constructor.
     */
    public function __construct(string $file)
    {
        $this->file = $file;
        if(is_file($file))
            $this->json = json_decode(file_get_contents($file));
        else{
            file_put_contents($file,'[]');
            $this->json = json_decode('[]');
        }
    }

    /**
     * write on file.
     */
    private function write(){
        file_put_contents($this->file,json_encode($this->json),JSON_PRETTY_PRINT);
    }

    /**
     * insert new user.
     * @param Stage $stage
     * @return bool
     */
    public function insertUser(Stage $stage): bool
    {
        foreach($this->json as $row){
            if($row->chat_id == Update::asObject()->message->chat->id)
                return false;
        }

        $this->json[]  = ["chat_id"=>Update::asObject()->message->chat->id,"stage"=>serialize($stage)];
        $this->write();
        return true;
    }

    /**
     * get the Stage
     * @return Stage|bool
     */
    public function getStage(): Stage|bool
    {
        foreach($this->json as $row){
            if($row->chat_id == Update::asObject()->message->chat->id)
                return unserialize($row->stage);
        }
        return false;
    }

    /**
     * update the Stage.
     * @param Stage $stage
     * @return bool
     */
    public function updateStage(Stage $stage): bool
    {
        foreach($this->json as $rowKey => $rowVal){
            if($rowVal->chat_id == Update::asObject()->message->chat->id) {
                $this->json[$rowKey]->stage = serialize($stage);
                $this->write();
                return true;
            }
        }
        return false;
    }
}