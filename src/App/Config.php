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

class Config
{
    private static $config = null;
    private array $configArr = [];


    /**
     * Config constructor. if you want to create a new config, use Config::init() instead.
     */
    private function __construct(){}

    /**
     * add a config to the config array. if the key is already set, it will be overwritten.
     * @param array $config the config array
     */
    public static function add(array $config) : void
    {
        if(self::$config == null)
            self::$config = new Config();

        foreach ($config as $cfgKey => $cfgVal)
            self::$config->configArr[strtolower($cfgKey)] = $cfgVal;
    }

    /**
     * get a config by key. if the key is not set, it will throw an exception.
     * @param string $configKey the key of the config
     * @throws Exception
     */
    public static function get(string $configKey) : mixed
    {
        $configKey = strtolower($configKey);
        Config::validate([$configKey]);
        if(isset(self::$config->configArr[$configKey]))
            return self::$config->configArr[$configKey];
        else
            throw new Exception('Config Not Found : failed to get key ['.$configKey.'] in ['.self::$config->configName.'] config');
    }


    /**
     * validate the config keys [only checks it is set]. if the key is not set, it will throw an exception.
     * @param array $configKeys the keys of the config
     * @throws Exception
     */
    public static function validate(array $configKeys): void
    {
        foreach ($configKeys as $configKey){
            if(!isset(self::$config->configArr[$configKey]))
                throw new Exception('Config Not Found : failed to get key ['.$configKey.'] in ['.self::$config->configName.'] config');

        }
    }
    /**
     * check if a config key is set
     * @param string $configKey the key of the config
     * @return bool
     */
    public static function isSet(string $configKey) : bool
    {
        return isset(self::$config->configArr[$configKey]);
    }

}