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

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws Exception
     */
    public function get(string $configKey) : mixed
    {
        if(isset($this->config[$configKey]))
            return $this->config[$configKey];
        else
            throw new Exception("Config Error : failed to get key $configKey");
    }

}