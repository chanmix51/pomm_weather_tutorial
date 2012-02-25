<?php #web/index.php

$service = require __DIR__."/../config/init.php";

$connection = $service
    ->getDatabase()
    ->createConnection();

$city_map = $connection
    ->getMapFor('Greg\Weather\City');

$cities = $city_map->findAll();

?>
<html>
  <body>
    <div id="city_form">
      <form>
        <select name="city">
<?php foreach ($cities as $city): ?>
            <option value="<?php echo $city->get('name') ?>"><?php echo $city['name'] ?></option>
<?php endforeach ?>
        </select>
      </form>
    </div>
  </body>
</html>

