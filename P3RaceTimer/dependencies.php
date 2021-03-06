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
    $p3Parser = new P3RaceTimer\Service\P3Parser;
    return $p3Parser;
};

// Event loop
$container['loop'] = function ($c) {
    $loop = React\EventLoop\Factory::create();

    // Force garbage collection
    $loop->addPeriodicTimer(
        1,
        function () {
            gc_collect_cycles();
        }
    );

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
$container['eventPush'] = function ($c) {
    $settings           = $c->get('settings');
    $loop               = $c->get('loop');
    $context            = new React\ZMQ\Context($loop);

    $push = $context->getSocket(ZMQ::SOCKET_PUSH);
    $push->connect('tcp://127.0.0.1:'.$settings['eventSocket']['port']);

    return $push;
};

// Event server socket for receiving events
$container['eventPull'] = function ($c) {
    $settings           = $c->get('settings');
    $loop               = $c->get('loop');
    $context            = new React\ZMQ\Context($loop);

    $pull = $context->getSocket(ZMQ::SOCKET_PULL);
    $pull->bind('tcp://127.0.0.1:'.$settings['eventSocket']['port']);

    return $pull;
};

$container['webSocketPusher'] = function ($c) {
    $settings   = $c->get('settings');
    $pusher     = new P3RaceTimer\Service\WebSocketPusher;
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

// Doctrine
$container['em'] = function ($c) {
    $settings = $c->get('settings');
    $service = new P3RaceTimer\Service\Doctrine($settings['doctrine']);
    $entityManager = $service->entityManager();
    return $entityManager;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[P3RaceTimer\Console\Capture::class] = function ($c) {
    return new P3RaceTimer\Console\Capture(
        $c->get('climate'),
        $c->get('p3Parser'),
        $c->get('p3SocketPromise'),
        $c->get('eventPush'),
        $c->get('loop'),
        $c->get('em')->getRepository("P3RaceTimer\Entity\DecoderMessage")
    );
};

$container[P3RaceTimer\Console\EventProcessor::class] = function ($c) {
    return new P3RaceTimer\Console\EventProcessor(
        $c->get('climate'),
        $c->get('eventPull'),
        $c->get('webSocketPusher'),
        $c->get('loop'),
        $c->get('em')->getRepository("P3RaceTimer\Entity\Transponder"),
        $c->get('em')->getRepository("P3RaceTimer\Entity\Passing"),
        $c->get('em')->getRepository("P3RaceTimer\Entity\Lap")
    );
};

$container[P3RaceTimer\Console\ImportParticipants::class] = function ($c) {
    return new P3RaceTimer\Console\ImportParticipants(
        $c->get('climate'),
        $c->get('em')->getRepository("P3RaceTimer\Entity\Transponder"),
        $c->get('em')->getRepository("P3RaceTimer\Entity\Team"),
        $c->get('em')->getRepository("P3RaceTimer\Entity\Participant")
    );
};
