<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\Participant;
use P3RaceTimer\Entity\Team;
use P3RaceTimer\Entity\Transponder;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function findByName($name)
    {
        return $this->findOneBy(["name" => $name]);
    }

    public function create($name)
    {
        $team = new Team();

        $team->setName($name);

        return $team;
    }

    public function getOrCreateTeam($name)
    {
        $team = $this->findByName($name);

        if (!$team) {
            $team = $this->create($name);
            $this->prepare($team);
        }

        return $team;
    }

    public function prepare(Team $team)
    {
        $this->_em->persist($team);
    }

    public function savePrepared()
    {
        $this->_em->flush();
    }

    public function save(Team $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    public function delete(Team $team)
    {
        $this->_em->remove($team);
        $this->_em->flush();
    }
}
