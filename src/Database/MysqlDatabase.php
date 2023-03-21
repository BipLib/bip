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
create table if not exists `state`(
id         int auto_increment primary key,
chat_id    bigint  not null comment 'user chat id',
join_date timestamp default NOW() not null,
stage_call text null comment 'stage to be called',
stages     json null comment 'array of user stage',
constraint state_pk2
    unique (chat_id));
");

        if (self::$instance->getUser($chat_id) !== false)
            return false;

        $stmt = self::$instance->pdo->prepare("insert into state (chat_id,stage_call,stages) values (?,?,?)");
        return $stmt->execute([$chat_id, $stage::class, json_encode([$stage::class => $stage])]);

    }

    /**
     * @inheritDoc
     */
    public function updateStage(int $chat_id, Stage $stage): bool
    {
        $user = self::$instance->getUser($chat_id);
        if ($user === false)
            return false;
        $user['stages'][$stage::class] = $stage;
        $user['stage_call'] = $stage::class;
        $stmt = $this->pdo->prepare("update state set stage_call = ?, stages = ? where chat_id = ?");
        return $stmt->execute([$user['stage_call'], json_encode($user['stages']), $chat_id]);

    }

    public function getUser(int $chat_id): array|bool
    {
        $stmt = self::$instance->pdo->prepare("select * from state where chat_id = ?");
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
        $stmt = self::$instance->pdo->prepare("insert into $table (" . implode(',', $keys) . ") values (" . implode(',', array_fill(0, count($values), '?')) . ")");
        return $stmt->execute($values);
    }
}