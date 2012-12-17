<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use RatchetApp\PredisHelper;

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

/**
 * Websockets
 */
$app->get('/websockets', function () use ($app) {
    return $app['twig']->render('websockets.html.twig');
})->bind('websockets');

/**
 * Redis pubsub
 */
$app->match('/pubsub', function (Request $request) use ($app) {
	if ($request->getMethod() == 'GET') {
		return $app['twig']->render('pubsub.html.twig');
	}

    if ($request->request->get('pub') && $request->request->get('channel')) {
    	$channel = $request->request->get('channel');
    	$payload = $request->request->get('pub');

    	$pr = new PredisHelper();
    	$pr->publish($channel, $payload);
    	
    	return new Response(sprintf('Published %s to %s', $payload, $channel));
    }
    
    return new Response("Need pub and channel", 400);
    
})
->method('GET|POST')
->bind('pubsub');

$app->run();