<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="objective", type="text")
     */
    private $objective;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text")
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="requirements", type="text")
     */
    private $requirements;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectStatus")
     * @ORM\JoinColumn(name="project_status_id", referencedColumnName="id")
     */
    protected $projectStatus;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="projects")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    protected $company;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="idea_end_date", type="datetime")
     */
    private $ideaEndDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="video_end_date", type="datetime")
     */
    private $videoEndDate;

    /**
     * @var string
     *
     * @ORM\Column(name="total_prize", type="decimal")
     */
    private $totalPrize;

    /**
     * @var string
     *
     * @ORM\Column(name="image_thumb", type="string", length=150)
     */
    private $imageThumb;

    /**
     * @ORM\OneToMany(targetEntity="Idea", mappedBy="project")
     */
    protected $ideas;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="project")
     */
    protected $videos;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="users")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Prize", mappedBy="project")
     */
    protected $prizes;

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
     * @return Project
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
     * Set objective
     *
     * @param string $objective
     * @return Project
     */
    public function setObjective($objective)
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * Get objective
     *
     * @return string 
     */
    public function getObjective()
    {
        return $this->objective;
    }

    /**
     * Set detail
     *
     * @param string $detail
     * @return Project
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
     * Set requirements
     *
     * @param string $requirements
     * @return Project
     */
    public function setRequirements($requirements)
    {
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * Get requirements
     *
     * @return string 
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * Set companyId
     *
     * @param integer $companyId
     * @return Project
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return integer 
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Project
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
     * Set ideaEndDate
     *
     * @param \DateTime $ideaEndDate
     * @return Project
     */
    public function setIdeaEndDate($ideaEndDate)
    {
        $this->ideaEndDate = $ideaEndDate;

        return $this;
    }

    /**
     * Get ideaEndDate
     *
     * @return \DateTime 
     */
    public function getIdeaEndDate()
    {
        return $this->ideaEndDate;
    }

    /**
     * Set videoEndDate
     *
     * @param \DateTime $videoEndDate
     * @return Project
     */
    public function setVideoEndDate($videoEndDate)
    {
        $this->videoEndDate = $videoEndDate;

        return $this;
    }

    /**
     * Get videoEndDate
     *
     * @return \DateTime 
     */
    public function getVideoEndDate()
    {
        return $this->videoEndDate;
    }

    /**
     * Set totalPrize
     *
     * @param string $totalPrize
     * @return Project
     */
    public function setTotalPrize($totalPrize)
    {
        $this->totalPrize = $totalPrize;

        return $this;
    }

    /**
     * Get totalPrize
     *
     * @return string 
     */
    public function getTotalPrize()
    {
        return $this->totalPrize;
    }

    /**
     * Set imageThumb
     *
     * @param string $imageThumb
     * @return Project
     */
    public function setImageThumb($imageThumb)
    {
        $this->imageThumb = $imageThumb;

        return $this;
    }

    /**
     * Get imageThumb
     *
     * @return string 
     */
    public function getImageThumb()
    {
        return $this->imageThumb;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ideas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prizes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set projectStatus
     *
     * @param \Clube\SiteBundle\Entity\ProjectStatus $projectStatus
     * @return Project
     */
    public function setProjectStatus(\Clube\SiteBundle\Entity\ProjectStatus $projectStatus = null)
    {
        $this->projectStatus = $projectStatus;

        return $this;
    }

    /**
     * Get projectStatus
     *
     * @return \Clube\SiteBundle\Entity\ProjectStatus 
     */
    public function getProjectStatus()
    {
        return $this->projectStatus;
    }

    /**
     * Set company
     *
     * @param \Clube\SiteBundle\Entity\Company $company
     * @return Project
     */
    public function setCompany(\Clube\SiteBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \Clube\SiteBundle\Entity\Company 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add ideas
     *
     * @param \Clube\SiteBundle\Entity\Idea $ideas
     * @return Project
     */
    public function addIdea(\Clube\SiteBundle\Entity\Idea $ideas)
    {
        $this->ideas[] = $ideas;

        return $this;
    }

    /**
     * Remove ideas
     *
     * @param \Clube\SiteBundle\Entity\Idea $ideas
     */
    public function removeIdea(\Clube\SiteBundle\Entity\Idea $ideas)
    {
        $this->ideas->removeElement($ideas);
    }

    /**
     * Get ideas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdeas()
    {
        return $this->ideas;
    }

    /**
     * Add videos
     *
     * @param \Clube\SiteBundle\Entity\Video $videos
     * @return Project
     */
    public function addVideo(\Clube\SiteBundle\Entity\Video $videos)
    {
        $this->videos[] = $videos;

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Clube\SiteBundle\Entity\Video $videos
     */
    public function removeVideo(\Clube\SiteBundle\Entity\Video $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add users
     *
     * @param \Clube\SiteBundle\Entity\User $users
     * @return Project
     */
    public function addUser(\Clube\SiteBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Clube\SiteBundle\Entity\User $users
     */
    public function removeUser(\Clube\SiteBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add prizes
     *
     * @param \Clube\SiteBundle\Entity\Prize $prizes
     * @return Project
     */
    public function addPrize(\Clube\SiteBundle\Entity\Prize $prizes)
    {
        $this->prizes[] = $prizes;

        return $this;
    }

    /**
     * Remove prizes
     *
     * @param \Clube\SiteBundle\Entity\Prize $prizes
     */
    public function removePrize(\Clube\SiteBundle\Entity\Prize $prizes)
    {
        $this->prizes->removeElement($prizes);
    }

    /**
     * Get prizes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrizes()
    {
        return $this->prizes;
    }
}
