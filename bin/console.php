<?php
require __DIR__ . '/../vendor/autoload.php';

if (PHP_SAPI == 'cli') {
    // Get path from arguments
    $argv = $GLOBALS['argv'];
    array_shift($argv);
    $pathInfo       = implode('/', $argv);

    // Get the settings array
    $settings = require __DIR__ . '/../P3RaceTimer/settings.php';

    // Create a mock environment to route through Slim, add it to the settings array
    $env = \Slim\Http\Environment::mock([
      'REQUEST_URI' => '/' . $pathInfo
    ]);
    $settings['environment'] = $env;

    // Instantiate the app
    $app = new \Slim\App($settings);

    // Set up error handling
    $container = $app->getContainer();
    $container['errorHandler'] = function ($c) {
        return function ($request, $response, $exception) use ($c) {
            $x      = $response->getBody();
            $x->write("error");
            $x->write($exception->getMessage());
            return $response->withBody($x);
        };
    };

    $container['notFoundHandler'] = function ($c) {
        return function ($request, $response) use ($c) {
            $x      = $response->getBody();
            $x->write("command not found");
            return $response->withBody($x);
        };
    };

    // Set up dependencies
    require __DIR__ . '/../P3RaceTimer/dependencies.php';

    // Register routes
    require __DIR__ . '/../P3RaceTimer/routes.console.php';

    // Run!
    $app->run();
}
