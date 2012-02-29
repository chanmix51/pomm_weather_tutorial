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
        $sql = <<<EOSQL
SELECT 
  %s,
  c1.coords <-> c2.coords AS distance
FROM 
  %s c1,
  %s c2
WHERE 
    c2.name = ?
ORDER BY 
  distance ASC
LIMIT ?
EOSQL;

        $sql = sprintf($sql, 
            join(', ', $this->getSelectFields('c1')),
            $this->getTableName(),
            $this->getTableName()
        );

        return $this->query($sql, array($city_name, $limit + 1));
    }

    public function findAllName()
    {
        $sql = sprintf("SELECT name FROM %s ORDER BY name ASC", $this->getTableName());

        return $this->query($sql);
    }
}
