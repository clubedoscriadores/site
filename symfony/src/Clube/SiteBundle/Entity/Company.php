<?php

namespace Clube\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Company
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
     * @ORM\Column(name="site", type="string", length=100)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=100)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=300)
     */
    private $detail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="company")
     */
    protected $projects;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="companies")
     * @ORM\JoinTable(name="users_companies",
     *     joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $logoPath;

    public function getLogoAbsolutePath()
    {
        return null === $this->logoPath
            ? null
            : $this->getLogoRootDir().'/'.$this->id.'.'.$this->logoPath;
    }

    public function getLogoWebPath()
    {
        return null === $this->logoPath
            ? null
            : $this->getLogoDir().'/'.$this->id.'.'.$this->logoPath;
    }

    protected function getLogoRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getLogoDir();
    }

    protected function getLogoDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/company/logo';
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $logo;

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getLogo()
    {
        return $this->logo;
    }

    private $tempLogo;


    /**
     * Sets logo.
     *
     * @param UploadedFile $logo
     */
    public function setLogo(UploadedFile $logo = null)
    {
        $this->logo = $logo;
        // check if we have an old image path
        if (is_file($this->getLogoAbsolutePath())) {
            // store the old name to delete after the update
            $this->tempLogo = $this->getLogoAbsolutePath();
        } else {
            $this->logoPath = 'initial';
        }
    }


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $backgroundPath;

    public function getBackgroundAbsolutePath()
    {
        return null === $this->backgroundPath
            ? null
            : $this->getBackgroundRootDir().'/'.$this->id.'.'.$this->backgroundPath;
    }

    public function getBackgroundWebPath()
    {
        return null === $this->backgroundPath
            ? null
            : $this->getBackgroundDir().'/'.$this->id.'.'.$this->backgroundPath;
    }

    protected function getBackgroundRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getBackgroundDir();
    }

    protected function getBackgroundDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/company/background';
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $background;

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getBackground()
    {
        return $this->background;
    }

    private $tempBackground;


    /**
     * Sets background.
     *
     * @param UploadedFile $background
     */
    public function setBackground(UploadedFile $background = null)
    {
        $this->background = $background;
        // check if we have an old image path
        if (is_file($this->getBackgroundAbsolutePath())) {
            // store the old name to delete after the update
            $this->tempBackground = $this->getBackgroundAbsolutePath();
        } else {
            $this->backgroundPath = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getLogo()) {
            $this->logoPath = $this->getLogo()->guessExtension();
        }
        if (null !== $this->getBackground()) {
            $this->backgroundPath = $this->getBackground()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getLogo()) {
            return;
        }

        // check if we have an old image
        if (isset($this->tempLogo)) {
            // delete the old image
            unlink($this->tempLogo);
            // clear the temp image path
            $this->tempLogo = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getLogo()->move(
            $this->getLogoRootDir(),
            $this->id.'.'.$this->getLogo()->guessExtension()
        );

        $this->setLogo(null);

        if (null === $this->getBackground()) {
            return;
        }

        // check if we have an old image
        if (isset($this->tempBackground)) {
            // delete the old image
            unlink($this->tempBackground);
            // clear the temp image path
            $this->tempBackground = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getBackground()->move(
            $this->getBackgroundRootDir(),
            $this->id.'.'.$this->getBackground()->guessExtension()
        );

        $this->setBackground(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->tempLogo = $this->getLogoAbsolutePath();
        $this->tempBackground = $this->getBackgroundAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->tempLogo)) {
            unlink($this->tempLogo);
        }

        if (isset($this->tempBackground)) {
            unlink($this->tempBackground);
        }
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
     * @return Company
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
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Company
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
     * Constructor
     */
    public function __construct()
    {
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createDate = new \DateTime('now');
    }

    /**
     * Add projects
     *
     * @param \Clube\SiteBundle\Entity\Project $projects
     * @return Company
     */
    public function addProject(\Clube\SiteBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Clube\SiteBundle\Entity\Project $projects
     */
    public function removeProject(\Clube\SiteBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add users
     *
     * @param \Clube\SiteBundle\Entity\User $users
     * @return Company
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
     * Set site
     *
     * @param string $site
     * @return Company
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Company
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
     * Set detail
     *
     * @param string $detail
     * @return Company
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
     * Set path
     *
     * @param string $path
     * @return Project
     */
    public function setLogoPath($path)
    {
        $this->logoPath = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Project
     */
    public function setBackgroundPath($path)
    {
        $this->backgroundPath = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getBackgroundPath()
    {
        return $this->backgroundPath;
    }
}
