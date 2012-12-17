<?php

namespace RatchetApp;

use Predis;

class PredisHelper {
	
	private $redis;

	public function __construct() {
		$this->redis = new Predis\Client('tcp://127.0.0.1:6379');
	}

	public function publish($channel, $payload) {
		$this->redis->publish($channel, $payload);
	}
}