<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\service\WebSocketPusher;
use React;
use Ratchet;

use Slim\Http\Request;
use Slim\Http\Response;

final class EventProcessor
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

                $messageData = \json_decode($message, true);

                if (isset($messageData["source"])) {
                    if ($messageData["source"] == "p3connection") {
                        $this->notifyWebSocket($messageData["event"], $messageData["record"]);
                    }
                }
            });
        });

        $this->loop->run();
    }

    public function notifyWebSocket($topic, $eventData)
    {
        $this->webSocketPusher->onEvent($topic, $eventData);
    }
}
