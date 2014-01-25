<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prize
 *
 * @ORM\Table(name="prize")
 * @ORM\Entity
 */
class Prize
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
     * @ORM\ManyToOne(targetEntity="PrizeType")
     * @ORM\JoinColumn(name="prize_type_id", referencedColumnName="id")
     */
    protected $prizeType;

    /**
     * @var integer
     *
     * @ORM\Column(name="prize_place", type="integer")
     */
    private $prizePlace;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="prizes")
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
     * Set prizeType
     *
     * @param \Clube\SiteBundle\Entity\PrizeType $prizeType
     * @return Prize
     */
    public function setPrizeType(\Clube\SiteBundle\Entity\PrizeType $prizeType = null)
    {
        $this->prizeType = $prizeType;

        return $this;
    }

    /**
     * Get prizeType
     *
     * @return \Clube\SiteBundle\Entity\PrizeType 
     */
    public function getPrizeType()
    {
        return $this->prizeType;
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
}
