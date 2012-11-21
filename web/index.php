<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views'
));

$app['facebook_app_id'] = '193820144076033';
$app['facebook_app_secret'] = 'c5b04d7ac809cc3edb5b339917aacbf9';
$app['facebook_domain'] = 'dev.silex-test';

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