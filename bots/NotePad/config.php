<?php

use Bip\App\Config;
use Bip\Database\LazyJsonDatabase;
//hi
Config::add([
    'token'     => 'your bot api key',
    'admins'    => [12345678,123456789],
    'database'  => [
        'driver' => LazyJsonDatabase::class,
        'args' => [
            'file'=>'database.json'
        ]
    ]
]);