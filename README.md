# Websockets, Ratchet, Redis pubsub test

This is a proof of concept *HTML5 websocket app* showing how **ratchet and websockets** can be used along with **Redis' pubsub** publish and subscribe.

You can use the form below to subscribe/unsubscribe to a channel.  When you subscribe you also subscribe to the corresponding Redis channel.  So if using the cli you connect to Redis and do a `PUBLISH channel:jmoz foo`, the message will be published from Redis to the websocket server then to the browser.

You can publish via websockets to the WS server which will then broadcast to connected clients.  This does not touch Redis.

You can make an AJAX request to this app which will then use Redis and `PUBLISH` which will be picked up by the WS server and broadcast to clients.  This is a way to demo the raw Redis pubsub without using the cli.

The following tech is used:

- [Ratchet](http://socketo.me/) - the core WAMP app that uses an event loop, handles websockets and subscribes to Redis.
- [Predis-async](https://github.com/nrk/predis-async) - the client library for Redis that implements the [react](http://reactphp.org/) event loop.
- [Silex](http://silex.sensiolabs.org/) - used as the framework and provides the AJAX endpoint to publish via Redis.
- [AutobahnJS](http://autobahn.ws/js) - the JS library to implement websockets.


Source code is [on github jmoz/silex-test](https://github.com/jmoz/silex-test).  App is running at [silex-test.jmoz.co.uk/pubsub](http://silex-test.jmoz.co.uk/pubsub).

**Developed by [James Morris](http://jmoz.co.uk).  [Blog](http://blog.jmoz.co.uk).  [@jwmoz](http://twitter.com/jwmoz).**