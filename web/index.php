<?php #web/index.php
header("Content-Type: text/html; charset=UTF-8");

$service = require __DIR__."/../config/init.php";

$connection = $service
    ->getDatabase()
    ->createConnection();

$logger = new Pomm\Tools\Logger();
$connection
  ->registerFilter(new Pomm\FilterChain\LoggerFilter($logger));
register_shutdown_function(function() use ($logger) {
    $fh = fopen(__DIR__.'/../sql.logs', 'a+');
    fwrite($fh, print_r($logger->getLogs(), true));
    fclose($fh);
});

$city_map = $connection
    ->getMapFor('Greg\Weather\City');

$cities = $city_map->findAllName();

if (isset($_GET['city'])) {
    $selected_cities = $city_map
      ->getNearestCitiesFrom($_GET['city'], 5);
    $selected_city = $selected_cities->current();
}
?>
<html>
  <body>
    <div id="city_form">
      <form>
        <select name="city">
<?php foreach ($cities as $city): ?>
            <option value="<?php echo $city->get('name') ?>"><?php echo $city['name'] ?></option>
<?php endforeach ?>
        <input type="submit" />
        </select>
      </form>
    </div>
  </body>
</html>

<?php if (isset($selected_cities)): ?>
<div>
<div style="float:left;width:542px;">
<p><strong><?php echo $selected_city['name'] ?></strong></p>
  <p><img src="http://www.google.com<?php echo $selected_city['weather_probe']['condition'] ?>" /></p><p>T(celsius) <?php echo $selected_city['weather_probe']['temperature'] ?> Wind (<?php echo $selected_city['weather_probe']['wind_speed'] ?> km/h <?php echo $selected_city['weather_probe']['wind_direction'] ?>)</p>
  <img src="<?php printf("http://maps.googleapis.com/maps/api/staticmap?center=%s,%s&zoom=8&size=512x512&maptype=roadmap&sensor=false", $selected_city['coords']->x, $selected_city['coords']->y) ?>" />
</div>
<div>
<h3>Nearest cities around:</h3>
<ul>
<?php foreach($selected_cities as $city): ?>
<?php if ($selected_cities->isFirst()) continue ?>
<li><a href="?city=<?php echo $city->get('name') ?>"><?php echo $city['name'] ?></a> <?php echo $city['distance'] ?> km (<?php echo $city['weather_probe']['temperature'] ?> celsius)</li>
<?php endforeach ?>
</ul>
</div>
</div>
<?php endif ?>
