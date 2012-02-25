<?php

namespace Greg\Weather;

use Greg\Weather\Base\WeatherProbeMap as BaseWeatherProbeMap;
use Greg\Weather\WeatherProbe;
use \Pomm\Exception\Exception;
use \Pomm\Query\Where;

class WeatherProbeMap extends BaseWeatherProbeMap
{
    const GOOGLE_URL = "http://www.google.com/ig/api?weather=%s&hl=fr";

    public function createFromGoogle(City $city)
    {
        $data = utf8_encode(
          file_get_contents(
            sprintf(self::GOOGLE_URL, urlencode($city->get('name')))));
        $xml = simplexml_load_string($data);

        if (! $xml instanceof \SimpleXMLElement ) {
            throw new \RuntimeException(
              sprintf("Error while getting XML for city '%s' with URL '%s'.", 
                $city->getName(), 
                self::GOOGLE_URL));
        }

        if (isset($xml->weather->problem_cause['data'])) {
            throw new \RuntimeException(
              sprintf("No data available for city '%s'.", 
                $city->getName()));
        }

        $weather_probe = $this->createObject();
        $weather_probe->setCityName($city->get('name'));
        $weather_probe->hydrateFromXml($xml);
        $this->saveOne($weather_probe);
    }
}
