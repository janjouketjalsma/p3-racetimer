<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\Service\P3Parser;
use React;
use React\EventLoop\LoopInterface;
use React\Promise;
use React\Promise\PromiseInterface;
use React\Socket\ConnectionInterface;

use Slim\Http\Request;
use Slim\Http\Response;

final class Capture
{

    protected $climate;
    protected $p3Parser;
    protected $p3ConnectorPromise;
    protected $eventSocketPromise;

    public function __construct(CLImate $climate, P3Parser $p3Parser, PromiseInterface $p3ConnectorPromise, PromiseInterface $eventSocketPromise, LoopInterface $loop)
    {
        $this->climate              = $climate;
        $this->p3Parser             = $p3Parser;
        $this->p3ConnectorPromise   = $p3ConnectorPromise;
        $this->eventSocketPromise   = $eventSocketPromise;
        $this->loop                 = $loop;
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

    protected function capture(ConnectionInterface $p3Connector, React\Datagram\Socket $eventSocket)
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
        $records = $this->p3Parser->trimData($data);
        $completeRecords = $this->p3Parser->getRecords($records);

        foreach ($completeRecords as $record) {
            $record = $this->p3Parser->parse($record);

            if ($record) {
                $this->climate->dump($record);

                // Example: output record date as string
                $recordTime = \DateTime::createFromFormat('U', round($record["RTC_TIME"] / 1000000));
                $this->climate->out('Got record with date: ' . $recordTime->format('Y-m-d H:i:s'));

                // Send record to eventSocket
                $eventSocket->send(json_encode([
                  "source" => "p3connection",
                  "event" => $record["type_string"],
                  "record" => $record
                ]));
            }
        }
    }
}
