<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IdeaPrize
 *
 * @ORM\Table(name="idea_prize")
 * @ORM\Entity
 */
class IdeaPrize
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prize_amount", type="decimal")
     */
    private $prizeAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="prize_place", type="integer")
     */
    private $prizePlace;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="ideaPrizes")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\OneToOne(targetEntity="Idea")
     */
    protected $idea;

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
     * Set prizeAmount
     *
     * @param string $prizeAmount
     * @return Prize
     */
    public function setPrizeAmount($prizeAmount)
    {
        $this->prizeAmount = $prizeAmount;

        return $this;
    }

    /**
     * Get prizeAmount
     *
     * @return string 
     */
    public function getPrizeAmount()
    {
        return $this->prizeAmount;
    }

    /**
     * Set prizePlace
     *
     * @param integer $prizePlace
     * @return Prize
     */
    public function setPrizePlace($prizePlace)
    {
        $this->prizePlace = $prizePlace;

        return $this;
    }

    /**
     * Get prizePlace
     *
     * @return integer 
     */
    public function getPrizePlace()
    {
        return $this->prizePlace;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Prize
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set project
     *
     * @param \Clube\SiteBundle\Entity\Project $project
     * @return Prize
     */
    public function setProject(\Clube\SiteBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Clube\SiteBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set idea
     *
     * @param \Clube\SiteBundle\Entity\Idea $idea
     * @return IdeaPrize
     */
    public function setIdea(\Clube\SiteBundle\Entity\Idea $idea = null)
    {
        $this->idea = $idea;

        return $this;
    }

    /**
     * Get idea
     *
     * @return \Clube\SiteBundle\Entity\Idea 
     */
    public function getIdea()
    {
        return $this->idea;
    }
}
