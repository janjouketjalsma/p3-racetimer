<?php
namespace P3RaceTimer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Passing
 *
 * @ORM\Table(name="Passing")
 * @ORM\Entity(repositoryClass="P3RaceTimer\Entity\PassingRepository")
 */
class Passing
{
  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $passingNumber;

    /**
     * @ORM\ManyToOne(targetEntity="Transponder", inversedBy="passings")
     */
    private $transponder;

    /**
    * @ORM\Column(type="bigint")
    */
    private $rtc;

    /**
     * @ORM\OneToOne(targetEntity="Lap", mappedBy="startPassing")
     */
    private $startsLap;

    /**
     * @ORM\OneToOne(targetEntity="Lap", mappedBy="finishPassing")
     */
    private $finishesLap;

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

    public function setPassingNumber($passingNumber)
    {
        $this->passingNumber = $passingNumber;

        return $this;
    }

    public function getPassingNumber()
    {
        return $this->passingNumber;
    }

    /**
     * Set transponder
     *
     * @param \P3RaceTimer\Entity\Transponder $transponder
     *
     * @return \P3RaceTimer\Entity\Passing
     */
    public function setTransponder(\P3RaceTimer\Entity\Transponder $transponder)
    {
        $this->transponder = $transponder;

        return $this;
    }

    /**
     * Get transponder
     *
     * @return \P3RaceTimer\Entity\Transponder
     */
    public function getTransponder()
    {
        return $this->transponder;
    }

    /**
     * Set rtc
     *
     * @param string $rtc
     *
     * @return \P3RaceTimer\Entity\Passing
     */
    public function setRtc($rtc)
    {
        $this->rtc = $rtc;

        return $this;
    }

    /**
     * Get rtc
     *
     * @return string
     */
    public function getRtc()
    {
        return $this->rtc;
    }

    /**
     * Get startsLap
     *
     * @return \P3RaceTimer\Entity\Lap
     */
    public function getStartsLap()
    {
        return $this->startsLap;
    }

    /**
     * Get finishesLap
     *
     * @return \P3RaceTimer\Entity\Lap
     */
    public function getFinishesLap()
    {
        return $this->finishesLap;
    }
}
