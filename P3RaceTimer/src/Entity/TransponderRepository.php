<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\Transponder;

use Doctrine\ORM\EntityRepository;

class TransponderRepository extends EntityRepository
{
    public function findByIdentifier($identifier)
    {
        return $this->findOneBy(["identifier" => $identifier]);
    }

    public function create($transponderIdentifier)
    {
        $transponder = new Transponder();
        $transponder  ->setIdentifier($transponderIdentifier);

        return $transponder;
    }

    public function getOrCreateTransponder($transponderIdentifier)
    {
        $transponder = $this->findByIdentifier($transponderIdentifier);

        if (!$transponder) {
            $transponder = $this->create($transponderIdentifier);
            $this->prepare($transponder);
        }

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
