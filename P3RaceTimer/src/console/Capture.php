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

    public function __construct(CLImate $climate, P3Parser $p3Parser, Socket $p3Socket)
    {
        $this->climate  = $climate;
        $this->p3Parser = $p3Parser;
        $this->p3Socket = $p3Socket;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $this->climate->out('Capturing data from '.$this->p3Socket->getSockName());
        while (true) {
            $record = $this->p3Socket->read(1024);

            $this->handleRecord($record);
            die();
        }
    }

    protected function handleRecord($record)
    {
        $data = $this->p3Parser->parse($record);
        $this->climate->dump($data);
    }
}
