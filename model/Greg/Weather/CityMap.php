<?php

namespace Greg\Weather;

use Greg\Weather\Base\CityMap as BaseCityMap;
use Greg\Weather\City;
use \Pomm\Exception\Exception;
use \Pomm\Query\Where;

class CityMap extends BaseCityMap
{
    public function getNearestCitiesFrom($city_name, $limit)
    {

        $sql = <<<_
WITH
  ranked_weather_probe AS (
    SELECT 
      %s,
      rank() OVER created_at_window AS ranking
    FROM 
      %s
    WINDOW 
      created_at_window AS 
        (PARTITION BY city_name ORDER BY created_at DESC)
)
SELECT 
  %s,
  wp AS weather_probe,
  c1.coords <-> c2.coords AS distance
FROM 
  %s c1,
  %s c2
    JOIN ranked_weather_probe wp ON 
        c2.name = wp.city_name 
      AND 
        wp.ranking = 1
WHERE 
    c1.name = ?
ORDER BY 
  distance ASC
LIMIT ?
_;

        $weather_probe_map = $this->connection
          ->getMapFor('Greg\Weather\WeatherProbe');
        $weather_probe_table  = $weather_probe_map->getTableName();
        $weather_probe_fields = join(', ', $weather_probe_map->getSelectFields());

        $city_fields = join(', ', $this->getSelectFields('c2'));
        $city_table  = $this->getTableName();

        $sql = sprintf($sql, 
            $weather_probe_fields,
            $weather_probe_table,
            $city_fields,
            $city_table,
            $city_table
        );

        $collection = $this->query($sql, array($city_name, $limit + 1));

        $this->addVirtualField('weather_probe', 'WeatherProbe');

        return $collection;
    }
}
