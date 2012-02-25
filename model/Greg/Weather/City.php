<?php

namespace Greg\Weather;

use \Pomm\Object\BaseObject;
use \Pomm\Exception\Exception;

class City extends BaseObject
{
    public function getDistance()
    {
        return $this->has('distance') ? (int) (((float) $this->get('distance') * pi() * 6371 ) / 180)  : null;
    }
}
