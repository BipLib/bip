<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bip\Telegram\Update;

class Mapper
{
    protected object $object;
    protected array $objectsMap;

    public function __construct(object $object)
    {
        $this->object = $object;
    }
    public function __get(string $name)
    {
        if(isset($this->objectsMap[$name]) && class_exists($this->objectsMap[$name]))
            return new $this->objectsMap[$name]($this->object->{$name});
        elseif(isset($this->object->{$name}))
            return $this->object->{$name};
        else
            return null;
    }

}