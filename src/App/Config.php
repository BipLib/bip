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
    private array $config;
    private string $configName;

    public function __construct(array $config,string $configName = '')
    {
        foreach ($config as $cfgKey => $cfgVal)
            $this->config[strtolower($cfgKey)] = $cfgVal;

        $this->configName = $configName;
    }

    /**
     * get a config by key.
     * @throws Exception
     */
    public function get(string $configKey) : mixed
    {
        if(isset($this->config[$configKey]))
            return $this->config[$configKey];
        else
            throw new Exception("Config Not Found : failed to get key [$configKey] in [$this->configName] config");
    }

    /**
     * get config name.
     * @return string
     */
    public function getName(): string
    {
        return $this->configName;
    }

    /**
     * validate the config keys [only checks it is set].
     * @param array $configKeys
     * @throws Exception
     */
    public function validate(array $configKeys){
        foreach ($configKeys as $configKey){
            if(!isset($this->config[$configKey]))
                throw new Exception("Config Not Found : failed to get key [$configKey] in [$this->configName] config");

        }
    }

}