<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views'
));

/**
 * Index
 */
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('home');

/**
 * Facebook
 */
$app->get('/facebook', function () use ($app) {
    return $app['twig']->render('facebook.html.twig');
})->bind('facebook');

$app->run();