<?php
namespace P3RaceTimer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lap
 *
 * @ORM\Table(name="Lap")
 * @ORM\Entity(repositoryClass="P3RaceTimer\Entity\LapRepository")
 */
class Lap
{
  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Passing", inversedBy="startsLap")
     */
    private $startPassing;

    /**
     * @ORM\OneToOne(targetEntity="Passing", inversedBy="finishesLap")
     */
    private $finishPassing;

    /**
    * @ORM\Column(type="bigint")
    */
    private $rtcDiff;

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
      * Get created
      *
      * @return \DateTime
      */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set startPassing
     *
     * @param \P3RaceTimer\Entity\Passing
     *
     * @return \P3RaceTimer\Entity\Lap
     */
    public function setStartPassing(\P3RaceTimer\Entity\Passing $passing)
    {
        $this->startPassing = $passing;

        return $this;
    }

    /**
     * Get startPassing
     *
     * @return \P3RaceTimer\Entity\Passing
     */
    public function getStartPassing()
    {
        return $this->startPassing;
    }

    /**
     * Set finishPassing
     *
     * @param \P3RaceTimer\Entity\Passing
     *
     * @return \P3RaceTimer\Entity\Lap
     */
    public function setFinishPassing(\P3RaceTimer\Entity\Passing $passing)
    {
        $this->finishPassing = $passing;

        return $this;
    }

    /**
     * Get finishPassing
     *
     * @return \P3RaceTimer\Entity\Passing
     */
    public function getFinishPassing()
    {
        return $this->finishPassing;
    }

    /**
     * Set rtcDiff
     *
     * @param string $rtcDiff
     *
     * @return \P3RaceTimer\Entity\Lap
     */
    public function setRtcDiff($rtcDiff)
    {
        $this->rtcDiff = $rtcDiff;

        return $this;
    }

    /**
     * Get rtcDiff
     *
     * @return string
     */
    public function getRtcDiff()
    {
        return $this->rtcDiff;
    }
}
