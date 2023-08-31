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

class MysqlDatabase implements Database
{
    private static $instance = null;

    private \PDO $pdo;


    private function __construct(string $host, string $user, string $pass, string $db)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);

    }
    public static function init(string $host, string $user, string $pass, string $db): MysqlDatabase
    {
        if (self::$instance === null)
            self::$instance = new MysqlDatabase($host, $user, $pass, $db);
        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    public function insertUser(int $chat_id, Stage $stage): bool
    {
        self::$instance->pdo->exec("
CREATE TABLE IF NOT EXISTS `state`
(
    `chat_id`    BIGINT NOT NULL COMMENT 'User chat_id',
    `join_date`  TIMESTAMP DEFAULT NOW() NOT NULL,
    `stage_call` TEXT NULL COMMENT 'Stage to be called',
    `stages`     JSON NULL COMMENT 'Array of user stages',
    CONSTRAINT `state_pk`
        PRIMARY KEY (`chat_id`)
);
");

        if (self::$instance->getUser($chat_id) !== false)
            return false;

        $stmt = self::$instance->pdo->prepare("INSERT INTO `state` (chat_id,stage_call,stages) VALUES (?,?,?)");
        return $stmt->execute([$chat_id, str_replace(Bot::$stagePath,'',$stage::class), json_encode([str_replace(Bot::$stagePath,'',$stage::class) => $stage])]);

    }

    /**
     * @inheritDoc
     */
    public function updateStage(int $chat_id, Stage $stage): bool
    {
        $user = self::$instance->getUser($chat_id);
        if ($user === false)
            return false;
        $user['stages'][str_replace(Bot::$stagePath,'',$stage::class)] = $stage;
        $user['stage_call'] = str_replace(Bot::$stagePath,'',$stage::class);
        $stmt = $this->pdo->prepare("UPDATE `state` SET stage_call = ?, stages = ? WHERE chat_id = ?");
        return $stmt->execute([$user['stage_call'], json_encode($user['stages']), $chat_id]);

    }

    public function getUser(int $chat_id): array|bool
    {
        $stmt = self::$instance->pdo->prepare("SELECT * FROM `state` WHERE chat_id = ?");
        $stmt->execute([$chat_id]);
        $output =  $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($output === false)
            return false;
        $output['stages'] = json_decode($output['stages'], true);
        return $output;
    }

    /**
     * get pdo instance.
     * @return \PDO
     */
    public static function getPdo(): \PDO
    {
        return self::$instance->pdo;
    }
    public static function insert(string $table, array $data): bool
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $stmt = self::$instance->pdo->prepare("INSERT INTO `$table` (" . implode(',', $keys) . ") VALUES (" . implode(',', array_fill(0, count($values), '?')) . ")");
        return $stmt->execute($values);
    }
}