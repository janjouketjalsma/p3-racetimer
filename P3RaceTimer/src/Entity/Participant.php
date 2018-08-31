<?php
namespace P3RaceTimer\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="Participant")
 * @ORM\Entity(repositoryClass="P3RaceTimer\Entity\ParticipantRepository")
 */
class Participant
{
  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $prefix;

    /**
    * @ORM\Column(type="string", unique=true)
    */
    private $participantIdentifier;

    /**
     * @ORM\OneToMany(targetEntity="Transponder", mappedBy="participant")
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Participant
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Participant
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     *
     * @return Participant
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set participantIdentifier
     *
     * @param string $participantIdentifier
     *
     * @return Participant
     */
    public function setParticipantIdentifier($participantIdentifier)
    {
        $this->participantIdentifier = $participantIdentifier;

        return $this;
    }

    /**
     * Get participantIdentifier
     *
     * @return string
     */
    public function getParticipantIdentifier()
    {
        return $this->participantIdentifier;
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
