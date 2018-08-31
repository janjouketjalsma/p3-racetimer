<?php
namespace P3RaceTimer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="Team")
 * @ORM\Entity(repositoryClass="P3RaceTimer\Entity\TeamRepository")
 */
class Team
{
  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Transponder", mappedBy="team", cascade={"persist"})
     */
    private $transponders;

    /**
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="team")
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity="Lap", mappedBy="team")
     */
    private $laps;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transponders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->laps         = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add transponder
     *
     * @param \P3RaceTimer\Entity\Transponder $transponder
     *
     * @return \P3RaceTimer\Entity\Team
     */
    public function addTransponder(\P3RaceTimer\Entity\Transponder $transponder)
    {
        $this->transponders[] = $transponder;

        return $this;
    }

    /**
     * Remove transponder
     *
     * @param \P3RaceTimer\Entity\Transponder $transponder
     */
    public function removeTransponder(\P3RaceTimer\Entity\Transponder $transponder)
    {
        $this->transponders->removeElement($transponder);
    }

    /**
     * Get passings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransponders()
    {
        return $this->transponders;
    }

    /**
     * Get passings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Get team
     *
     * @return \P3RaceTimer\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Get passings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLaps()
    {
        return $this->laps;
    }
}
