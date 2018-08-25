<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\service\P3Parser;
use Socket\Raw\Socket;

use Slim\Http\Request;
use Slim\Http\Response;

final class Capture
{

    protected $climate;
    protected $p3Parser;
    protected $p3Socket;
    protected $eventServerSocket;

    public function __construct(CLImate $climate, P3Parser $p3Parser, Socket $p3Socket, Socket $eventServerSocket)
    {
        $this->climate = $climate;
        $this->p3Parser = $p3Parser;
        $this->p3Socket = $p3Socket;
        $this->eventServerSocket = $eventServerSocket;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->climate->out('Capturing data from ' . $this->p3Socket->getSockName());
        while (true) {
            $data = $this->p3Socket->read(1024);

            $this->handleData($data);
        }
    }

    protected function handleData($data)
    {
        $records = $this->p3Parser->trimData($data);
        $completeRecords = $this->p3Parser->getRecords($records);

        foreach ($completeRecords as $record) {
            $record = $this->p3Parser->parse($record);

            if ($record) {
                $this->climate->dump($record);

                // Example: output record date as string
                $recordTime = \DateTime::createFromFormat('U', round($record["RTC_TIME"] / 1000000));
                $this->climate->out('Got record on ' . $recordTime->format('Y-m-d H:i:s'));

                // Emit event
                $this->eventServerSocket->write(json_encode([
                    "type" => "INFO",
                    "message" => "Received record from decoder"
                ]));

                if($this->eventServerSocket->selectWrite()){
                    $this->eventServerSocket->write(json_encode([
                        "type" => "INFO",
                        "message" => "Received record from decoder"
                    ]));
                    $this->climate->out('Pushing event');
                }else{
                    $this->climate->out('Cannot push event');
                }

            }

        }

    }
}
