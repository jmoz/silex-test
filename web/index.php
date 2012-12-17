<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use RatchetApp\PredisHelper;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$env = getenv('APP_ENV') ?: 'prod';
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/$env.json", array(
	'domain' => getenv('APP_DOMAIN')
)));
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

/**
 * Websockets
 */
$app->get('/websockets', function () use ($app) {
    return $app['twig']->render('websockets.html.twig', array('ws_domain' => $app['ws_domain'], 'ws_port' => $app['app.chat.port']));
})->bind('websockets');

/**
 * Redis pubsub
 */
$app->match('/pubsub', function (Request $request) use ($app) {
	if ($request->getMethod() == 'GET') {
		return $app['twig']->render('pubsub.html.twig', array('ws_domain' => $app['ws_domain'], 'ws_port' => $app['ws_port']));
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