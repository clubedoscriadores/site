<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Idea
 *
 * @ORM\Table(name="idea")
 * @ORM\Entity
 */
class Idea
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
     * @ORM\Column(name="title", type="string", length=150)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text")
     */
    private $detail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="ideas")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_winner", type="boolean")
     */
    private $isWinner;

    /**
     * @var integer
     *
     * @ORM\Column(name="prize_id", type="integer")
     */
    private $prizeId;


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
     * Set title
     *
     * @param string $title
     * @return Idea
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return Idea
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Idea
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
     * Set projectId
     *
     * @param integer $projectId
     * @return Idea
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set isWinner
     *
     * @param boolean $isWinner
     * @return Idea
     */
    public function setIsWinner($isWinner)
    {
        $this->isWinner = $isWinner;

        return $this;
    }

    /**
     * Get isWinner
     *
     * @return boolean 
     */
    public function getIsWinner()
    {
        return $this->isWinner;
    }

    /**
     * Set prizeId
     *
     * @param integer $prizeId
     * @return Idea
     */
    public function setPrizeId($prizeId)
    {
        $this->prizeId = $prizeId;

        return $this;
    }

    /**
     * Get prizeId
     *
     * @return integer 
     */
    public function getPrizeId()
    {
        return $this->prizeId;
    }

    /**
     * Set project
     *
     * @param \Clube\SiteBundle\Entity\Project $project
     * @return Idea
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
