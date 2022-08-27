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


trait Request
{
    /**
     * bot api key;
     * @var string
     */
    private string $token;

    /**
     * set bot api key.
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function call(string $method , array $params = []):mixed
    {
        $ch = curl_init('https://api.telegram.org/bot'.$this->token.'/'.$method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        return json_decode(curl_exec($ch));
    }

}