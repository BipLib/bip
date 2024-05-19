<?php

use Bip\App\Config;
use Bip\Database\Mysql;

Config::add([
    'token'     => 'BIP_BOT_TOKEN',
    'admins'    => [1234567,12345678],
    'database'=>[
        'driver' => Mysql::class,
        'args'=>[
            'host' => 'mysql',
            'pass' => 'BIP_DATABASE_PASSWORD',
            'user' => 'root',
            'db' => 'BIP_DATABASE_NAME',
        ]
    ],
]);