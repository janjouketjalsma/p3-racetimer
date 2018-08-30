<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\Service\WebSocketPusher;
use React;
use Ratchet;
use P3RaceTimer\Entity\TransponderRepository;
use P3RaceTimer\Entity\PassingRepository;

use Slim\Http\Request;
use Slim\Http\Response;

final class EventProcessor
{

    protected $climate;
    protected $eventSocketPromise;
    protected $webSocketPusher;
    protected $loop;
    protected $transponderRepository;
    protected $passingRepository;

    public function __construct(
        CLImate $climate,
        React\Promise\PromiseInterface $eventSocketPromise,
        WebSocketPusher $webSocketPusher,
        React\EventLoop\LoopInterface $loop,
        TransponderRepository $transponderRepository,
        PassingRepository $passingRepository
    ) {
        $this->climate                = $climate;
        $this->eventSocketPromise     = $eventSocketPromise;
        $this->webSocketPusher        = $webSocketPusher;
        $this->loop                   = $loop;
        $this->transponderRepository  = $transponderRepository;
        $this->passingRepository      = $passingRepository;

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
                        // Notify websocket with record update
                        $this->notifyWebSocket($messageData["event"], $messageData["record"]);

                        // Process message
                        $this->processP3message($messageData["event"], $messageData["record"]);
                    }
                }
            });
        });

        $this->loop->run();
    }

    protected function notifyWebSocket($topic, $eventData)
    {
        $this->webSocketPusher->onEvent($topic, $eventData);
    }

    protected function processP3message($type, $eventData)
    {
        if ($type == "PASSING") {
            $transponder = $this->getOrCreateTransponder($eventData["TRANSPONDER"]);

            $passing = $this->passingRepository->create(
                $eventData["PASSING_NUMBER"],
                $transponder,
                $eventData["RTC_TIME"]
            );

            $this->passingRepository->save($passing);
        }
    }

    protected function getOrCreateTransponder($transponderId)
    {
        $transponder = $this->transponderRepository->findById($transponderId);

        if (!$transponder) {
            $transponder = $this->transponderRepository->create($transponderId);
        }

        return $transponder;
    }
}
