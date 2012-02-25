<?php #config/get_weather_report.php

$service = require __DIR__."/../config/init.php";

$connection = $service
    ->getDatabase()
    ->createConnection();

$city_map = $connection
    ->getMapFor("Greg\Weather\City");

$probe_xml_map = $connection
    ->getMapFor("Greg\Weather\ProbeXml");

$cities = $city_map->findAll();

foreach($cities as $city) {
    try {
        $probe_xml = $probe_xml_map
          ->createObject(array(
            'city_name' => $city->get('name'),
            'xml_response' => utf8_encode(file_get_contents(sprintf("http://www.google.com/ig/api?weather=%s&hl=fr", urlencode($city->get('name')))))
        ));
        $probe_xml_map->saveOne($probe_xml);
        sleep(1);
    } catch(\Pomm\Exception\Exception $e) {
        printf("Error wile processing city '%s'.\n Driver said '%s'.\nIgnoring.\n", $city['name'], $e->getMessage());
    }
}

