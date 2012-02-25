<?php # bin/generate_model.php

$service = require(__DIR__."/../config/init.php");

$scan = new Pomm\Tools\ScanSchemaTool(array(
    'schema' => 'weather',
    'database' => $service->getDatabase(),
    'prefix_dir' => __DIR__."/../model",

    ));
$scan->execute();
