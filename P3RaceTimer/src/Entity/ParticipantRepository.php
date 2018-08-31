<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\Participant;
use P3RaceTimer\Entity\Team;
use P3RaceTimer\Entity\Transponder;
use Doctrine\ORM\EntityRepository;

class ParticipantRepository extends EntityRepository
{
    public function findByIdentifier($participantIdentifier)
    {
        return $this->findOneBy(["participantIdentifier" => $participantIdentifier]);
    }

    public function create($participantIdentifier, $firstName, $lastName, $prefix)
    {
        $participant = new Participant();

        $participant  ->setParticipantIdentifier($participantIdentifier)
                      ->setFirstName($firstName)
                      ->setLastName($lastName)
                      ->setPrefix($prefix);

        return $participant;
    }

    public function prepare(Participant $participant)
    {
        $this->_em->persist($participant);
    }

    public function savePrepared()
    {
        $this->_em->flush();
    }

    public function save(Participant $participant)
    {
        $this->_em->persist($participant);
        $this->_em->flush();
    }

    public function delete(Participant $participant)
    {
        $this->_em->remove($participant);
        $this->_em->flush();
    }
}
