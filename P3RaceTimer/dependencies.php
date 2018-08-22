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

// P3 TCP socket
$container['p3Socket'] = function ($c) {
    $settings = $c->get('settings');
    $factory  = new \Socket\Raw\Factory();
    $p3Socket   = $factory->createClient('tcp://'.$settings['p3Socket']['host']);
    return $p3Socket;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[P3RaceTimer\Console\Capture::class] = function ($c) {
    return new P3RaceTimer\Console\Capture($c->get('climate'), $c->get('p3Parser'), $c->get('p3Socket'));
};
