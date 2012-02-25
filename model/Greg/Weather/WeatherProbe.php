<?php

namespace Greg\Weather;

use \Pomm\Object\BaseObject;
use \Pomm\Exception\Exception;

class WeatherProbe extends BaseObject
{
    public function hydrateFromXml(\SimpleXMLElement $xml)
    {
        $this->set('temperature', 
          (string) $xml
            ->weather
            ->current_conditions
            ->temp_c['data']);

        $wind_condition = 
          (string) $xml
            ->weather
            ->current_conditions
            ->wind_condition['data'];

        preg_match('#Vent : ([SNEO]{1,2}) à ([0-9]+) km/h#', $wind_condition, $matchs);
        $this->set('wind_direction', $matchs[1]);
        $this->set('wind_speed', $matchs[2]);

        preg_match('#Humidité : ([0-9]{1,3}) %#', 
          (string) $xml
            ->weather
            ->current_conditions
            ->humidity['data'],
          $matchs);

        $this->set('humidity', $matchs[1]);
        $this->set('condition', 
          (string) $xml
            ->weather
            ->current_conditions
            ->icon['data']);
    }
}
