<?php
namespace P3RaceTimer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DecoderMessage
 *
 * @ORM\Table(name="DecoderMessage")
 * @ORM\Entity(repositoryClass="P3RaceTimer\Entity\DecoderMessageRepository")
 */
class DecoderMessage
{
  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $raw;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $processed;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $created;

    public function __construct()
    {
        $this->created = new \DateTime();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set raw message
     *
     * @param string $raw
     *
     * @return DecoderMessage
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get raw message
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Set processed message
     *
     * @param array $processed
     *
     * @return DecoderMessage
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * Get processed message
     *
     * @return array
     */
    public function getProcessed()
    {
        return $this->processed;
    }

    /**
      * Get created
      *
      * @return \DateTime
      */
    public function getCreated()
    {
        return $this->created;
    }
}
