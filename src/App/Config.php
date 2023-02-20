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
    private array $configArr;

    /**
     * @param array $config
     * @return Config
     */
    public static function init(array $config) : Config
    {
        if(self::$config == null)
            self::$config = new Config($config);
        return self::$config;
    }

    /**
     * Config constructor.
     * @param array $config
     */
    private function __construct(array $config)
    {
        foreach ($config as $cfgKey => $cfgVal)
            self::$config->configArr[strtolower($cfgKey)] = $cfgVal;

    }

    /**
     * get a config by key.
     * @throws Exception
     */
    public static function get(string $configKey) : mixed
    {
        Config::validate([$configKey]);
        $configKey = strtolower($configKey);
        if(isset(self::$config->configArr[$configKey]))
            return self::$config->configArr[$configKey];
        else
            throw new Exception('Config Not Found : failed to get key ['.$configKey.'] in ['.self::$config->configName.'] config');
    }


    /**
     * validate the config keys [only checks it is set].
     * @param array $configKeys
     * @throws Exception
     */
    public static function validate(array $configKeys): void
    {
        foreach ($configKeys as $configKey){
            if(!isset(self::$config->configArr[$configKey]))
                throw new Exception('Config Not Found : failed to get key ['.$configKey.'] in ['.self::$config->configName.'] config');

        }
    }

}