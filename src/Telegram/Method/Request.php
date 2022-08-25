<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\Telegram\Method;


use Bip\App\Config;

trait Request
{

    public Config $_config;
    /**
     * call a telegram api method.
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function call(string $method , array $params = []):mixed
    {
        $ch = curl_init('https://api.telegram.org/bot'.$this->_config->get('token').'/'.$method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        return json_decode(curl_exec($ch));
    }

}