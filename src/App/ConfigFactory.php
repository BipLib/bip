<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\App;


use Exception;

class ConfigFactory
{
    private static array $configs;

    /**
     * create new config
     * @param string $configName
     * @param array $cfg
     * @return Config
     */
    public static function create(string $configName, array $cfg): Config
    {
        return self::$configs[] = new Config($cfg,$configName);
    }

    /**
     * get config
     * @throws Exception
     */
    public static function get(string $configName): Config
    {
        foreach(self::$configs as $config)
            if($config->getName() == $configName)
                return $config;
        throw new Exception("$configName config was not created by ConfigFactory");
    }

}