<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// CLI view
$container['climate'] = function ($c) {
    $climate = new League\CLImate\CLImate;
    return $climate;
};

// P3 parser
$container['p3Parser'] = function ($c) {
    $p3Parser = new P3RaceTimer\service\P3Parser;
    return $p3Parser;
};

// Event loop
$container['loop'] = function ($c) {
    $loop = React\EventLoop\Factory::create();

    return $loop;
};

// P3 TCP socket for listening to decoder
$container['p3SocketPromise'] = function ($c) {
    $settings   = $c->get('settings');
    $loop       = $c->get('loop');
    $connector  = new React\Socket\Connector($loop);

    $p3SocketPromise = $connector->connect('tcp://'.$settings['p3Socket']['host']);
    return $p3SocketPromise;
};

// Event socket for emitting events
$container['eventSocketPromise'] = function ($c) {
    $settings           = $c->get('settings');
    $loop               = $c->get('loop');
    $factory            = new React\Datagram\Factory($loop);

    $eventSocketPromise = $factory->createClient('localhost:'.$settings['eventSocket']['port']);

    return $eventSocketPromise;
};

$container['webSocketPusher'] = function ($c) {
    $settings   = $c->get('settings');
    $pusher     = new P3RaceTimer\service\WebSocketPusher;
    $loop       = $c->get('loop');
    $webSock    = new React\Socket\Server('0.0.0.0:'.$settings['webSocket']['port'], $loop); // Binding to 0.0.0.0 means remotes can connect
    $webServer  = new Ratchet\Server\IoServer(
        new Ratchet\Http\HttpServer(
            new Ratchet\WebSocket\WsServer(
                new Ratchet\Wamp\WampServer(
                    $pusher
                )
            )
        ),
        $webSock
    );

    return $pusher;
};

// Event server socket for receiving events
$container['eventServerSocketPromise'] = function ($c) {
    $settings           = $c->get('settings');
    $loop               = $c->get('loop');
    $factory            = new React\Datagram\Factory($loop);

    $eventServerSocketPromise = $factory->createServer('localhost:'.$settings['eventSocket']['port']);

    return $eventServerSocketPromise;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[P3RaceTimer\Console\Capture::class] = function ($c) {
    return new P3RaceTimer\Console\Capture(
        $c->get('climate'),
        $c->get('p3Parser'),
        $c->get('p3SocketPromise'),
        $c->get('eventSocketPromise'),
        $c->get('loop')
    );
};

$container[P3RaceTimer\Console\EventProcessor::class] = function ($c) {
    return new P3RaceTimer\Console\EventProcessor(
        $c->get('climate'),
        $c->get('eventServerSocketPromise'),
        $c->get('webSocketPusher'),
        $c->get('loop')
    );
};
