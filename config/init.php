<?php #config/init.php

$loader = require __DIR__."/../vendor/.composer/autoload.php";
$loader->add('Greg', __DIR__."/../model");

$service = new Pomm\Service(
    array(
        'default' => array(
            'dsn' => sprintf('pgsql://greg/greg', __DIR__)
        )
));

$service->getDatabase('default')
    ->registerConverter('Point', new Pomm\Converter\PgPoint(), array('point'));

return $service;
