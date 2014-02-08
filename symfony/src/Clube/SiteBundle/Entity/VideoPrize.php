<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VideoPrize
 *
 * @ORM\Table(name="video_prize")
 * @ORM\Entity
 */
class VideoPrize
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
     * @ORM\Column(name="prizeAmount", type="decimal")
     */
    private $prizeAmount;

    /**
     * @var integer
     *
     * @ORM\Column(name="prizePlace", type="integer")
     */
    private $prizePlace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\OneToOne(targetEntity="Video")
     */
    protected $video;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="videoPrizes")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    protected $project;


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
     * @return VideoPrize
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
     * @return VideoPrize
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
     * @return VideoPrize
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
     * Set video
     *
     * @param \Clube\SiteBundle\Entity\Video $video
     * @return VideoPrize
     */
    public function setVideo(\Clube\SiteBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \Clube\SiteBundle\Entity\Video 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set project
     *
     * @param \Clube\SiteBundle\Entity\Project $project
     * @return VideoPrize
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
