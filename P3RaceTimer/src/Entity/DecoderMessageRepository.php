<?php
namespace P3RaceTimer\Entity;

use P3RaceTimer\Entity\DecoderMessage;
use Doctrine\ORM\EntityRepository;

class DecoderMessageRepository extends EntityRepository
{
    public function findById($identifier)
    {
        return $this->find($identifier);
    }

    public function create(string $raw, array $processed = null)
    {
        $decoderMessage = new DecoderMessage();
        $decoderMessage ->setRaw($raw)
                        ->setProcessed($processed);

        return $decoderMessage;
    }

    public function prepare(DecoderMessage $decoderMessage)
    {
        $this->_em->persist($decoderMessage);
    }

    public function savePrepared()
    {
        $this->_em->flush();
    }

    public function save(DecoderMessage $decoderMessage)
    {
        $this->_em->persist($decoderMessage);
        $this->_em->flush();
    }

    public function delete(DecoderMessage $decoderMessage)
    {
        $this->_em->remove($decoderMessage);
        $this->_em->flush();
    }
}
