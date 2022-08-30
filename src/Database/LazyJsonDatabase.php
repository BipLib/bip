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
 * Class LazyJsonDatabase , [This database is slow. it is designed to help the development]
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
        if (is_file($file))
            $this->json = json_decode(file_get_contents($file));
        else {
            file_put_contents($file, '[]');
            $this->json = json_decode('[]');
        }
    }

    /**
     * write on file.
     */
    private function write()
    {
        file_put_contents($this->file, json_encode($this->json,JSON_PRETTY_PRINT));
    }

    public function insertUser(Stage $stage): bool
    {
        foreach ($this->json as $row) {
            if ($row->chat_id == Update::asObject()->message->chat->id)
                return false;
        }

        $this->json[] = ["chat_id" => Update::asObject()->message->chat->id, "stages" => [get_class($stage) => $stage]];
        $this->write();
        return true;
    }

    public function getStages(): object|bool
    {
        foreach ($this->json as $row) {
            if ($row->chat_id == Update::asObject()->message->chat->id)
                if (isset($row->stages))
                    return $row->stages;
        }
        return false;
    }

    public function updateStage(Stage $stage): bool
    {
        foreach ($this->json as $rowKey => $rowVal) {
            if ($rowVal->chat_id == Update::asObject()->message->chat->id) {
                $this->json[$rowKey]->stages->{get_class($stage)} = $stage;
                $this->write();
                return true;
            }
        }
        return false;
    }
}