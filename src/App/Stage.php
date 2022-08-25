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


abstract class Stage
{
    /**
     * automatically assigned.
     * @var Config
     */
    public Config $_config;
    /**
     * call a telegram api method.
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public abstract function call(string $method , array $params = []): mixed;

    /**
     * in every run of the Stage, controller automatically called.
     */
    public abstract function controller();
}