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
use Bip\Bot;

/**
 * Class LazyJsonDatabase , [This database is slow. it is designed to help the development]
 * @package Bip\Database
 */
class LazyJson implements Database
{

    private static $instance = null;
    private mixed $json;
    private string $file;

    /**
     * LazyJsonDatabase constructor.
     */
    private function __construct(){}
    public static function init(string $file): LazyJson
    {
        if (empty(self::$instance)) {
            self::$instance = new LazyJson();
            self::$instance->file = $file;
            if (is_file($file))
                self::$instance->json = json_decode(file_get_contents($file),true); //caution : if an error occurs in json file (e.g syntax error) all data in database will be removed.
            else {
                file_put_contents($file, '[]');
                self::$instance->json = [];
            }
        }
        return self::$instance;
    }

    /**
     * write on file.
     */
    private function write()
    {
        file_put_contents(self::$instance->file, json_encode(self::$instance->json,JSON_PRETTY_PRINT));
    }

    public function insertUser(int $chat_id ,Stage $stage): bool
    {
        foreach (self::$instance->json as $row)
            if ($row['chat_id'] == $chat_id)
                return false;


        self::$instance->json[]   = [
            'chat_id'   => $chat_id,
            'stage_call'=> str_replace(Bot::$stagePath,'',$stage::class),
            'stages'    => [str_replace(Bot::$stagePath,'',$stage::class) => $stage]
        ];
        self::$instance->write();
        return true;
    }

    public function getUser(int $chat_id): array|bool
    {
        foreach (self::$instance->json as $row)
            if ($row['chat_id'] == $chat_id)
                return $row;
        return false;
    }
    public function updateStage(int $chat_id,Stage $stage): bool
    {
        foreach (self::$instance->json as $rowKey => $rowVal) {
            if ($rowVal['chat_id'] == $chat_id) {
                self::$instance->json[$rowKey]['stages'][str_replace(Bot::$stagePath,'',$stage::class)] = $stage;
                self::$instance->json[$rowKey]['stage_call'] = str_replace(Bot::$stagePath,'',$stage::class);
                self::$instance->write();
                return true;
            }
        }
        return false;
    }
}
