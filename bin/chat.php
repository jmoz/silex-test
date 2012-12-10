<?php

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use RatchetApp\Chat;

require __DIR__.'/../vendor/autoload.php';

$server = IoServer::factory(
	new WsServer(
		new Chat()
	),
	8080
);

echo "Chat server starting...\n";
$server->run();