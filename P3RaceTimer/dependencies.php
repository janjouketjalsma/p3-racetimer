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

// P3 TCP socket for listening to decoder
$container['p3Socket'] = function ($c) {
    $settings = $c->get('settings');
    $factory  = new \Socket\Raw\Factory();
    $p3Socket   = $factory->createClient('tcp://'.$settings['p3Socket']['host']);
    return $p3Socket;
};

// Event socket for emitting events
$container['eventServerSocket'] = function ($c) {
    $settings = $c->get('settings');
    $factory  = new \Socket\Raw\Factory();
    $eventServerSocket = $factory->createServer('tcp://127.0.0.1:'.$settings['eventSocket']['port']);

    return $eventServerSocket;
};


// Event socket for emitting events
$container['eventClientSocket'] = function ($c) {
    $settings = $c->get('settings');
    $factory  = new \Socket\Raw\Factory();
    $eventClientSocket = $factory->createClient('tcp://127.0.0.1:'.$settings['eventSocket']['port']);

    return $eventClientSocket;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[P3RaceTimer\Console\Capture::class] = function ($c) {
    return new P3RaceTimer\Console\Capture($c->get('climate'), $c->get('p3Parser'), $c->get('p3Socket') , $c->get('eventServerSocket'));
};

$container[P3RaceTimer\Console\WebSocketServer::class] = function ($c) {
    return new P3RaceTimer\Console\WebSocketServer(
        $c->get('climate'),
        new P3RaceTimer\service\WebSocketPusher,
        $c->get('eventClientSocket')
    );
};

