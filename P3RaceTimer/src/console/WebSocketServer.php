<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\service\WebSocketPusher;
use React;
use Ratchet;

use Slim\Http\Request;
use Slim\Http\Response;

final class WebSocketServer
{

    protected $climate;
    protected $eventSocketPromise;
    protected $webSocketPusher;
    protected $loop;

    public function __construct(CLImate $climate, React\Promise\PromiseInterface $eventSocketPromise, WebSocketPusher $webSocketPusher, React\EventLoop\LoopInterface $loop)
    {
        $this->climate            = $climate;
        $this->eventSocketPromise = $eventSocketPromise;
        $this->webSocketPusher    = $webSocketPusher;
        $this->loop               = $loop;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->climate->out('Waiting for events on connection');

        $this->eventSocketPromise->then(function (React\Datagram\Socket $eventSocket) {
            $eventSocket->on('message', function ($message, $serverAddress, $eventSocket) {
                $this->climate->out('received "' . $message . '" from ' . $serverAddress);
                $this->webSocketPusher->onEvent($message);
            });
        });

        $this->loop->run();
    }
}
