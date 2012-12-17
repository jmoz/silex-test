<?php

namespace RatchetApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

	protected $clients;

	public function __construct() {
		$this->clients = new \SplObjectStorage();
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		echo "Client attached\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
    	echo "Message received: $msg\n";
    	foreach ($this->clients as $client) {
    		$client->send($msg);
    	}
    }

    public function onClose(ConnectionInterface $conn) {
    	$this->clients->detach($conn);
    	echo "Client detached\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    	echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}