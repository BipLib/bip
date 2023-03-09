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
     * previous node name.
     * @var string
     */
    public string $_prev = 'default';
    /**
     * next node name.
     * @var string
     */
    public string $_node = 'default';
    /**
     * in every run of the Stage, controller automatically called.
     */
    public abstract function controller();
    public function default(){}

}