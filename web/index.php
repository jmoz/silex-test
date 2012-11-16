<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
// $app['debug'] = true;

/**
 * Index
 */
$app->get('/', function () {
    return 'index';
});

$app->run();