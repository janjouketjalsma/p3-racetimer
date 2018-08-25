<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\service\WebSocketPusher;
use Socket\Raw\Socket;

use Slim\Http\Request;
use Slim\Http\Response;

final class WebSocketServer
{

    protected $climate;
    protected $eventClientSocket;
    private $webSocketPusher;

    public function __construct(CLImate $climate, WebSocketPusher $webSocketPusher, Socket $eventClientSocket)
    {
        $this->climate = $climate;
        $this->eventClientSocket = $eventClientSocket;
        $this->webSocketPusher = $webSocketPusher;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->climate->out('Waiting for events on connection with ' . $this->eventClientSocket->getSockName());

        while (true) {
            $data = $this->eventClientSocket->read(8192);
            $this->climate->dump(json_decode($data));
        }

        /*

        $loop   = React\EventLoop\Factory::create();

        $loop->addPeriodicTimer(1, function () {
            echo "Tick\n";
        });

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new React\Socket\Server('0.0.0.0:8080', $loop); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new Ratchet\Server\IoServer(
            new Ratchet\Http\HttpServer(
                new Ratchet\WebSocket\WsServer(
                    new Ratchet\Wamp\WampServer(
                        $this->webSocketPusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();*/
    }
}