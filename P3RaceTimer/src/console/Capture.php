<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\Service\P3Parser;
use React;
use React\EventLoop\LoopInterface;
use React\Promise;
use React\Promise\PromiseInterface;
use React\Socket\ConnectionInterface;
use P3RaceTimer\Entity\DecoderMessageRepository;

use Slim\Http\Request;
use Slim\Http\Response;

final class Capture
{

    protected $climate;
    protected $p3Parser;
    protected $p3ConnectorPromise;
    protected $eventSocketPromise;
    protected $decoderMessageRepository;

    public function __construct(
        CLImate $climate,
        P3Parser $p3Parser,
        PromiseInterface $p3ConnectorPromise,
        PromiseInterface $eventSocketPromise,
        LoopInterface $loop,
        DecoderMessageRepository $decoderMessageRepository
    ) {
        $this->climate                  = $climate;
        $this->p3Parser                 = $p3Parser;
        $this->p3ConnectorPromise       = $p3ConnectorPromise;
        $this->eventSocketPromise       = $eventSocketPromise;
        $this->loop                     = $loop;
        $this->decoderMessageRepository = $decoderMessageRepository;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        Promise\all([$this->p3ConnectorPromise, $this->eventSocketPromise])->then(function ($values) {
            $p3Connector = $values[0];
            $eventSocket = $values[1];
            $this->climate->out('Waiting for events on p3 connection');
            $this->capture($p3Connector, $eventSocket);
        });
        $this->loop->run();
    }

    protected function capture(ConnectionInterface $p3Connector, ConnectionInterface $eventSocket)
    {
        $p3Connector->on("data", function ($data) use ($eventSocket) {
            $this->handleData($data, $eventSocket);
        });
        $p3Connector->on("connection", function () {
            $this->climate->out('p3 connection established');
        });
    }

    protected function handleData($data, $eventSocket)
    {
        $records          = $this->p3Parser->trimData($data);
        $completeRecords  = $this->p3Parser->getRecords($records);

        foreach ($completeRecords as $record) {
            $parsedRecord = $this->p3Parser->parse($record);

            if ($parsedRecord) {
                //$this->climate->dump($parsedRecord);


                if ($parsedRecord["type_string"] == "PASSING") {
                    $this->climate->out("PASSING ".$parsedRecord["PASSING_NUMBER"]);
                }

                // Send record to eventSocket
                $eventSocket->write(json_encode([
                    "source" => "p3connection",
                    "event" => $parsedRecord["type_string"],
                    "record" => $parsedRecord
                ]));
            }
        }

        // Create message entity
        $decoderMessage = $this->decoderMessageRepository->create($data, $parsedRecord ?: null);

        // Prepare database insert
        $this->decoderMessageRepository->prepare($decoderMessage);

        // Insert messages into database
        $this->decoderMessageRepository->savePrepared();
    }
}
