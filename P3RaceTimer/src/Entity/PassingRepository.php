<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\Passing;
use P3RaceTimer\Entity\Transponder;
use Doctrine\ORM\EntityRepository;

class PassingRepository extends EntityRepository
{
    public function findById($identifier)
    {
        return $this->find($identifier);
    }

    public function create($passingNumber, Transponder $transponder, $rtc)
    {
        $passing = new Passing();
        $passing  ->setPassingNumber($passingNumber)
                  ->setTransponder($transponder)
                  ->setRtc($rtc);

        return $passing;
    }

    public function prepare(Passing $passing)
    {
        $this->_em->persist($passing);
    }

    public function savePrepared()
    {
        $this->_em->flush();
    }

    public function save(Passing $passing)
    {
        $this->_em->persist($passing);
        $this->_em->flush();
    }

    public function delete(Passing $passing)
    {
        $this->_em->remove($passing);
        $this->_em->flush();
    }
}
