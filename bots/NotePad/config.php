<?php

use Bip\App\Config;
use Bip\Database\LazyJson;

Config::add([
    'token'     => 'your bot api key',
    'admins'    => [12345678,123456789],
    'database'=>[
        'driver' => LazyJson::class,
        'args'=>[
        'file' => 'database.json'
        ]
    ]
]);