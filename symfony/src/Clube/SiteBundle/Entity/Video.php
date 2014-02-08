<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity
 */
class Video
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
     * @ORM\Column(name="link", type="string", length=150)
     */
    private $link;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="videos")
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
     * @ORM\OneToOne(targetEntity="VideoPrize")
     * @ORM\JoinColumn(name="video_prize_id", referencedColumnName="id")
     */
    protected $videoPrize;

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
     * @return Video
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
     * Set link
     *
     * @param string $link
     * @return Video
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * Set project
     *
     * @param \Clube\SiteBundle\Entity\Project $project
     * @return Video
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
     * Set videoPrize
     *
     * @param \Clube\SiteBundle\Entity\VideoPrize $videoPrize
     * @return Video
     */
    public function setVideoPrize(\Clube\SiteBundle\Entity\VideoPrize $videoPrize = null)
    {
        $this->videoPrize = $videoPrize;

        return $this;
    }

    /**
     * Get videoPrize
     *
     * @return \Clube\SiteBundle\Entity\VideoPrize 
     */
    public function getVideoPrize()
    {
        return $this->videoPrize;
    }
}
