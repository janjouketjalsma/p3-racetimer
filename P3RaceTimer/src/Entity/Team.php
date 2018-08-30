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
     * @ORM\OneToMany(targetEntity="Transponder", mappedBy="team")
     */
    private $transponders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transponders = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get team
     *
     * @return \P3RaceTimer\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
