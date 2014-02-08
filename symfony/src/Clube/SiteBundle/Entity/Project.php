<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\OneToMany(targetEntity="Idea", mappedBy="project")
     */
    protected $ideas;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxIdeas", type="integer")
     */
    private $maxIdeas;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="project")
     */
    protected $videos;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxVideos", type="integer")
     */
    private $maxVideos;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="users")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="IdeaPrize", mappedBy="project")
     */
    protected $ideaPrizes;

    /**
     * @ORM\OneToMany(targetEntity="VideoPrize", mappedBy="project")
     */
    protected $videoPrizes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->id.'.'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    private $temp;


    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->path = $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getFile()->guessExtension()
        );

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }
	
	public function renderBarReverse()
	{
		$dStart = $this->createDate;
		$dEnd = $this->videoEndDate;

        $iTotal = $dEnd->diff($dStart);
        $dStart = new \DateTime('now');
        $iPeriod = $dEnd->diff($dStart);
        if ($iTotal->days == 0)
            return '100%';
        return (100*$iPeriod->days/$iTotal->days);
	}

    public function renderBar()
    {
        $dStart = $this->createDate;
        $dEnd  = new \DateTime('now');
        if($this->ideaEndDate > new \DateTime('now'))
        {
            $dEnd = $this->ideaEndDate;
        }
        else
        {
            $dEnd = $this->videoEndDate;
        }

        $iTotal = $dEnd->diff($dStart);
        $dStart = new \DateTime('now');
        $iPeriod = $dEnd->diff($dStart);
        if ($iTotal->days == 0)
            return '100%';
        return (100-100*$iPeriod->days/$iTotal->days);
    }
	
	public function renderRemaining()
	{
		$dStart = $this->createDate;
        $dEnd  = new \DateTime('now');
        if($this->ideaEndDate > new \DateTime('now'))
        {
            $dEnd = $this->ideaEndDate;
        }
        else
        {
            $dEnd = $this->videoEndDate;
        }
		
		if ($dStart > $dEnd)
			return 'Finalizado';

        $iRest = $dEnd->diff(new \DateTime('now'));
		$lday = 'dias';
		
		if ($iRest->days == 1)
			return 'Último dia para enviar.';
				
		return 'Restam ' . $iRest->days . ' dias para enviar.';
	}
	
	public function getEndDate()
	{
		$dStart = $this->createDate;
        $dEnd  = new \DateTime('now');
        if($this->ideaEndDate > new \DateTime('now'))
        {
            $dEnd = $this->ideaEndDate;
        }
        else
        {
            $dEnd = $this->videoEndDate;
        }
		return $dEnd;
	}
	
	public function renderPositionIdea()
	{
		$dStart = $this->createDate;
		$dEnd = $this->videoEndDate;

        $iTotal = $dEnd->diff($dStart);
        $dStart = $this->createDate;
        $iPeriod = $this->ideaEndDate->diff($dStart);
        if ($iTotal->days == 0)
            return '100%';
        return (100*$iPeriod->days/$iTotal->days);
	}

    public function renderEndDate()
    {
        $dStart = new \DateTime('now');
        $dEnd  = new \DateTime('now');
        if($this->ideaEndDate > new \DateTime('now'))
        {
            $dEnd = $this->ideaEndDate;
        }
        else
        {
            $dEnd = $this->videoEndDate;
        }

        return $this->formatDateDiff($dStart, $dEnd).' para terminar';

    }

    public function formatDateDiff($start, $end=null) {
        if(!($start instanceof \DateTime)) {
            $start = new \DateTime($start);
        }

        if($end === null) {
            $end = new \DateTime();
        }

        if(!($end instanceof \DateTime)) {
            $end = new \DateTime($start);
        }

        $interval = $end->diff($start);
        $doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals

        $format = array();
        if($interval->y !== 0) {
            $format[] = "%y ".$doPlural($interval->y, "ano");
        }
        if($interval->m !== 0) {
            if($interval->m === 1)
                $format[] = "%m mês";
            else
                $format[] = "%m meses";
        }
        if($interval->d !== 0) {
            $format[] = "%d ".$doPlural($interval->d, "dia");
        }
        if($interval->h !== 0) {
            $format[] = "%h ".$doPlural($interval->h, "hora");
        }
        if($interval->i !== 0) {
            $format[] = "%i ".$doPlural($interval->i, "minuto");
        }
        if($interval->s !== 0) {
            if(!count($format)) {
                return "menos de um minuto";
            } else {
                $format[] = "%s ".$doPlural($interval->s, "segundo");
            }
        }

        // We use the two biggest parts
        if(count($format) > 1) {
            $format = array_shift($format);
        } else {
            $format = array_pop($format);
        }

        // Prepend 'since ' or whatever you like
        return $interval->format($format);
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
     * Set maxIdeas
     *
     * @param integer $maxIdeas
     * @return Project
     */
    public function setMaxIdeas($maxIdeas)
    {
        $this->maxIdeas = $maxIdeas;

        return $this;
    }

    /**
     * Get maxIdeas
     *
     * @return integer
     */
    public function getMaxIdeas()
    {
        return $this->maxIdeas;
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
     * Set maxVideos
     *
     * @param integer $maxVideos
     * @return Project
     */
    public function setMaxVideos($maxVideos)
    {
        $this->maxVideos = $maxVideos;

        return $this;
    }

    /**
     * Get maxVideos
     *
     * @return integer
     */
    public function getMaxVideos()
    {
        return $this->maxVideos;
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
     * Set path
     *
     * @param string $path
     * @return Project
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Add ideaPrizes
     *
     * @param \Clube\SiteBundle\Entity\IdeaPrize $ideaPrizes
     * @return Project
     */
    public function addIdeaPrize(\Clube\SiteBundle\Entity\IdeaPrize $ideaPrizes)
    {
        $this->ideaPrizes[] = $ideaPrizes;

        return $this;
    }

    /**
     * Remove ideaPrizes
     *
     * @param \Clube\SiteBundle\Entity\IdeaPrize $ideaPrizes
     */
    public function removeIdeaPrize(\Clube\SiteBundle\Entity\IdeaPrize $ideaPrizes)
    {
        $this->ideaPrizes->removeElement($ideaPrizes);
    }

    /**
     * Get ideaPrizes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdeaPrizes()
    {
        return $this->ideaPrizes;
    }

    /**
     * Add videoPrizes
     *
     * @param \Clube\SiteBundle\Entity\VideoPrize $videoPrizes
     * @return Project
     */
    public function addVideoPrize(\Clube\SiteBundle\Entity\VideoPrize $videoPrizes)
    {
        $this->videoPrizes[] = $videoPrizes;

        return $this;
    }

    /**
     * Remove videoPrizes
     *
     * @param \Clube\SiteBundle\Entity\VideoPrize $videoPrizes
     */
    public function removeVideoPrize(\Clube\SiteBundle\Entity\VideoPrize $videoPrizes)
    {
        $this->videoPrizes->removeElement($videoPrizes);
    }

    /**
     * Get videoPrizes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideoPrizes()
    {
        return $this->videoPrizes;
    }
}
