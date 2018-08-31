<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\Participant;
use P3RaceTimer\Entity\Team;
use P3RaceTimer\Entity\Transponder;
use P3RaceTimer\Entity\Lap;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;

class LapRepository extends EntityRepository
{
    public function findOpenLap($team)
    {
        $openLap = $this->createQueryBuilder('l')
                        ->where('l.finishPassing IS NULL')
                        ->andWhere('l.team = :teamId')
                        ->setParameter('teamId', $team->getId())
                        ->setMaxResults(1)
                        ->getQuery()
                        ->getOneOrNullResult();
        return $openLap;
    }

    public function create()
    {
        $lap = new Lap();

        return $lap;
    }

    public function prepare(Lap $lap)
    {
        $this->_em->persist($lap);
    }

    public function savePrepared()
    {
        $this->_em->flush();
    }

    public function save(Lap $lap)
    {
        $this->_em->persist($lap);
        $this->_em->flush();
    }

    public function delete(Lap $lap)
    {
        $this->_em->remove($lap);
        $this->_em->flush();
    }
}
