<?php

namespace RatchetApp;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    /**
     * A lookup of all the topics clients have subscribed to
     */
    public $subscribedTopics = array();
    protected $redis;

    public function init($client) {
        $this->redis = $client;
        echo "Connected to Redis, now listening for incoming messages...\n";
        
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "Pusher: onSubscribe\n";
        echo "Pusher: topic: $topic {$topic->count()}\n";
        // When a visitor subscribes to a topic link the Topic object in a  lookup array
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
            $pubsubContext = $this->redis->pubsub($topic->getId(), array($this, 'pubsub'));
            echo "Pusher: subscribed to topic $topic\n";
        }
    }

    /**
     * @param string
     */
    public function pubsub($event, $pubsub) {
        echo "Pusher: pubsub\n";
        echo "Pusher: kind: $event->kind channel: $event->channel payload: $event->payload\n";

        if (!array_key_exists($event->channel, $this->subscribedTopics)) {
            echo "Pusher: no subscribers, no broadcast\n";
            return;
        }

        $topic = $this->subscribedTopics[$event->channel];
        echo "Pusher: $event->channel: $event->payload {$topic->count()}\n";
        $topic->broadcast("$event->channel: $event->payload");

        // quit if we get the message from redis
        if (strtolower(trim($event->payload)) === 'quit') {
            echo "Pusher: quitting...\n";
            $pubsub->quit();
        }
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        echo "Pusher: onUnSubscribe\n";
        echo "Pusher: topic: $topic {$topic->count()}\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "Pusher: onOpen\n";
    }

    public function onClose(ConnectionInterface $conn) {
        echo "Pusher: onClose\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        echo "Pusher: onCall\n";
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        echo "Pusher: onPublish\n";
        $topic->broadcast("$topic: $event");
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Pusher: onError\n";
    }
}