<?php #config/init.php

$loader = require __DIR__."/../vendor/.composer/autoload.php";
$loader->add('Greg', __DIR__."/../model");

$service = new Pomm\Service(
    array(
        'default' => array(
            'dsn' => sprintf('pgsql://greg/greg', __DIR__)
        )
));

return $service;
