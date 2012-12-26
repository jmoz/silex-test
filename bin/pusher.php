<?php

use RatchetApp\Pusher;

require __DIR__ . '/../vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();
$pusher = new Pusher();

$loop->addPeriodicTimer(10, array($pusher, 'timedCallback'));

$client = new Predis\Async\Client('tcp://127.0.0.1:6379', $loop);
$client->connect(array($pusher, 'init'));

// Set up our WebSocket server for clients wanting real-time updates
$webSock = new React\Socket\Server($loop);
$webSock->listen(8080, '127.0.0.1');
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\WebSocket\WsServer(
        new Ratchet\Wamp\WampServer(
            $pusher
        )
    ),
    $webSock
);

echo "Pusher starting...\n";
$loop->run();