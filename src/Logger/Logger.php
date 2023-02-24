<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */


namespace Bip\Logger;

use Bip\App\Config;

/**
 * Class Logger
 * @package Bip\Logger
 */
class Logger
{


    private static $logger = null;
    private int $logCount ;

    private function __construct()
    {
    }

    /**
     * add a log to the log file.
     * @param mixed $logMessage
     * @param string $logType
     * @param string $logTag
     * @return void
     */
    public static function add(mixed $logMessage,string $logType = '',string $logTag = 'default'): void
    {
        if(empty(self::$logger))
            self::$logger = new Logger();

        Config::add(['logMaxCount' => 100]);
        self::$logger->logCount = Config::get('logMaxCount');

        //create logs directory if not exists and create log file if not exists
        if(!is_dir(__DIR__."/../../logs"))
            mkdir(__DIR__."/../../logs");
        if(!file_exists(__DIR__."/../../logs/$logTag.json.log"))
            file_put_contents(__DIR__."/../../logs/$logTag.json.log",'[]');


        $log = json_decode(file_get_contents(__DIR__."/../../logs/$logTag.json.log"));

        $log[] = [
            'type' => $logType,
            'time' => time(),
            'message' => $logMessage,
        ];

        // remove first log if log count is more than logCount
        if(count($log) > self::$logger->logCount)
            array_shift($log);

        file_put_contents(__DIR__."/../../logs/$logTag.json.log",json_encode($log,JSON_PRETTY_PRINT));


    }

    /**
     * get all logs from the log file.
     * @param string $logTag
     * @return array
     */
    public static function get(string $logTag = 'default'): array
    {
        if(empty(self::$logger))
            self::$logger = new Logger();

        if(!file_exists(__DIR__."/../../logs/$logTag..json.log"))
            return [];

        return json_decode(file_get_contents(__DIR__."/../../logs/$logTag.json.log"),true);
    }
    public static function clear(string $logTag = 'default'): void
    {
        if(empty(self::$logger))
            self::$logger = new Logger();

        if(!file_exists(__DIR__."/../../logs/$logTag.json.log"))
            return;

        file_put_contents(__DIR__."/../../logs/$logTag.json.log",'[]');
    }
    /**
     * change the log count.
     * @param int $logCount
     */
    public static function changeLogCount(int $logCount): void
    {
        if(empty(self::$logger))
            self::$logger = new Logger();

        self::$logger->logCount = $logCount;
    }

}
