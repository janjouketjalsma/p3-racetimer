<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\Service\WebSocketPusher;
use React;
use Ratchet;
use P3RaceTimer\Entity\TransponderRepository;
use P3RaceTimer\Entity\PassingRepository;
use P3RaceTimer\Entity\LapRepository;
use P3RaceTimer\Entity\Transponder;
use P3RaceTimer\Entity\Team;
use P3RaceTimer\Entity\Passing;

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
    protected $lapRepository;

    public function __construct(
        CLImate $climate,
        React\Promise\PromiseInterface $eventSocketPromise,
        WebSocketPusher $webSocketPusher,
        React\EventLoop\LoopInterface $loop,
        TransponderRepository $transponderRepository,
        PassingRepository $passingRepository,
        LapRepository $lapRepository
    ) {
        $this->climate                = $climate;
        $this->eventSocketPromise     = $eventSocketPromise;
        $this->webSocketPusher        = $webSocketPusher;
        $this->loop                   = $loop;
        $this->transponderRepository  = $transponderRepository;
        $this->passingRepository      = $passingRepository;
        $this->lapRepository          = $lapRepository;
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
            $transponder = $this->transponderRepository->getOrCreateTransponder($eventData["TRANSPONDER"]);

            $passing = $this->passingRepository->create(
                $eventData["PASSING_NUMBER"],
                $transponder,
                $eventData["RTC_TIME"]
            );

            $this->passingRepository->save($passing);

            $team = $transponder->getTeam();

            if ($team) {
                $this->startOrFinishLap($passing, $team);
            }
        }
    }

    protected function startOrFinishLap(Passing $passing, Team $team)
    {
        $openLap = $this->lapRepository->findOpenLap($team);

        if ($openLap) {
            $finishPassing  = $passing;
            $startPassing   = $openLap->getStartPassing($team);
            $rtcDiff        = $finishPassing->getRtc() - $startPassing->getRtc();

            $openLap->setFinishPassing($finishPassing);
            $openLap->setRtcDiff($rtcDiff);

            $this->lapRepository->save($openLap);

            // Notify websocket of finished lap
            $startParticipant = $startPassing->getTransponder()->getParticipant();
            if ($startParticipant) {
                $this->notifyWebSocket("FINISHED_LAP", [
                    "team" => $team->getName(),
                    "participant" => implode(
                        array_filter([
                            $startParticipant->getFirstName(),
                            $startParticipant->getPrefix(),
                            $startParticipant->getLastName()
                        ]),
                        " "
                    ),
                    "lapTime" => $openLap->getRtcDiff()
                ]);
            }
            return;
        }

        $newLap = $this->lapRepository->create();
        $newLap->setStartPassing($passing);
        $newLap->setTeam($team);

        $this->lapRepository->save($newLap);
    }
}
