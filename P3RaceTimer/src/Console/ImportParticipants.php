<?php

namespace P3RaceTimer\Console;

use League\CLImate\CLImate;
use P3RaceTimer\Entity\TransponderRepository;
use P3RaceTimer\Entity\TeamRepository;
use P3RaceTimer\Entity\ParticipantRepository;

use Slim\Http\Request;
use Slim\Http\Response;

use League\Csv\Reader;
use League\Csv\Statement;

final class ImportParticipants
{

    protected $climate;
    protected $transponderRepository;
    protected $teamRepository;
    protected $participantRepository;

    public function __construct(
        CLImate $climate,
        TransponderRepository $transponderRepository,
        TeamRepository $teamRepository,
        ParticipantRepository $participantRepository
    ){
        $this->climate                = $climate;
        $this->transponderRepository  = $transponderRepository;
        $this->teamRepository         = $teamRepository;
        $this->participantRepository  = $participantRepository;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        if (!isset($args['csvFile'])) {
            return $this->climate->error("Missing CSV file");
        }

        $csvFile = $args['csvFile'];

        if (!file_exists($csvFile)) {
            return $this->climate->error("Specified CSV file does not exist");
        }

        $reader = Reader::createFromPath($csvFile, 'r');
        $reader->setHeaderOffset(0);
        $reader->setDelimiter(";");

        $records = (new Statement())->process($reader);

        foreach ($records->getRecords() as $record) {
            $this->processRecord($record);
        }
    }

    public function processRecord($record)
    {
        //$this->climate->dump($record);

        $participant = $this->participantRepository->findByIdentifier($record["Participant identifier"]);

        if ($participant) {
            $this->climate->out("Skipping ".$record["Participant identifier"]." because it alreadyexists.");
            return;// Participants identifier exists
        }

        $participant = $this->participantRepository->create(
            $record["Participant identifier"],
            $record["First name"],
            $record["Last name"],
            $record["Prefix"]
        );

        $team = $this->teamRepository->getOrCreateTeam($record["Team"]);

        $participant->setTeam($team);

        $transponder = $this->transponderRepository->getOrCreateTransponder($record["Transponder"]);
        $transponder->setTeam($team);
        $transponder->setParticipant($participant);

        $this->climate->out("Processing ".$participant->getParticipantIdentifier());

        $this->participantRepository->save($participant);

        // $firstName, $lastName, $prefix, Team $team, Transponder $transponder
    }
}
