<?php #bin/get_weather_report.php

$service = require __DIR__."/../config/init.php";

$connection = $service
    ->getDatabase()
    ->createConnection();

$city_map = $connection
    ->getMapFor("Greg\Weather\City");

$weather_probe_map = $connection
    ->getMapFor("Greg\Weather\WeatherProbe");

$cities = $city_map->findAll();

foreach($cities as $city) {
    try {
        $weather_probe_map->createFromGoogle($city);
        printf("'%s' ok.\n", $city['name']);
        sleep(1);
    } catch(\RunTimeException $e) {
        printf("Error wile processing city '%s'.\nIgnoring.\n", $city['name']);
    }
}

