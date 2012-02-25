<?php #config/init.php

$loader = require __DIR__."/../vendor/.composer/autoload.php";
$loader->add('Greg', __DIR__."/../model");

$service = new Pomm\Service(
    array(
        'default' => array(
            'dsn' => 'pgsql://greg@172.16.0.1/greg'
        )
));

$service->getDatabase('default')
    ->registerConverter('Point', new Pomm\Converter\PgPoint(), array('point'))
    ->registerConverter('WeatherProbe', 
      new Pomm\Converter\PgEntity($service->getDatabase('default'), 'Greg\Weather\WeatherProbe'),
      array('weather.weather_probe'));

return $service;
