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
   */
    private $id;

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
     * Constructor
     */
    public function __construct()
    {
        $this->passings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
