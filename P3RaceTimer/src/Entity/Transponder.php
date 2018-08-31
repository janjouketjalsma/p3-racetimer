<?php
namespace P3RaceTimer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transponder
 *
 * @ORM\Table(name="Transponder")
 * @ORM\Entity(repositoryClass="P3RaceTimer\Entity\TransponderRepository")
 */
class Transponder
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
    private $identifier;

    /**
     * @ORM\OneToMany(targetEntity="Passing", mappedBy="transponder")
     */
    private $passings;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="transponders")
     * @ORM\JoinColumn(name="Team_id", referencedColumnName="id", nullable=true)
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="Participant", inversedBy="transponders")
     * @ORM\JoinColumn(name="Participant_id", referencedColumnName="id", nullable=true)
     */
    private $participant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->passings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return integer
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }



    /**
     * Get passings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassings()
    {
        return $this->passings;
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
