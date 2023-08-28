<?php

use Bip\App\Config;
use Bip\Database\LazyJsonDatabase;

Config::add([
    'token'     => 'your bot api key',
    'admins'    => [12345678,123456789],
    'database'  => LazyJsonDatabase::init('database.json.db'),
]);
