<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\Transponder;

use Doctrine\ORM\EntityRepository;

class TransponderRepository extends EntityRepository
{
    public function findById($identifier)
    {
        return $this->find($identifier);
    }

    public function create($transponderId)
    {
        $transponder = new Transponder();
        $transponder  ->setId($transponderId);

        return $transponder;
    }

    public function prepare(Transponder $transponder)
    {
        $this->_em->persist($transponder);
    }

    public function savePrepared()
    {
        $this->_em->flush();
    }

    public function save(Transponder $transponder)
    {
        $this->_em->persist($transponder);
        $this->_em->flush();
    }

    public function delete(Transponder $transponder)
    {
        $this->_em->remove($transponder);
        $this->_em->flush();
    }
}
