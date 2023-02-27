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
use stdClass;

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
            $this->json = json_decode(file_get_contents($file),true); //caution : if an error occurs in json file (e.g syntax error) all data in database will be removed.
        else {
            file_put_contents($file, '[]');
            $this->json = json_decode('[]'.true);
        }
    }

    /**
     * write on file.
     */
    private function write()
    {
        file_put_contents($this->file, json_encode($this->json,JSON_PRETTY_PRINT));
    }

    public function insertUser(int $chat_id ,Stage $stage): bool
    {
        foreach ($this->json as $row)
            if ($row['chat_id'] == $chat_id)
                return false;


        $this->json[]   = $arr = [
            'chat_id'   => $chat_id,
            'stage'     => $stage
        ];
        $this->write();
        return true;
    }

    public function getStageProperties(int $chat_id): array|bool
    {
        foreach ($this->json as $row)
            if ($row['chat_id'] == $chat_id)
                return $row['stage'] ?? [];
        return false;
    }

    public function updateStage(int $chat_id,object $stage): bool
    {
        foreach ($this->json as $rowKey => $rowVal) {
            if ($rowVal['chat_id'] == $chat_id) {
                $this->json[$rowKey]['stage'] = $stage;
                $this->write();
                return true;
            }
        }
        return false;
    }
}
