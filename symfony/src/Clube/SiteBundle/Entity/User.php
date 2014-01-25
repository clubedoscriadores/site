<?php
// src/Clube/SiteBundle/Entity/User.php

namespace Clube\SiteBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_profile")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=70)
     */
    private $name;

	/**
	* @var date $birthDate
	* @ORM\Column(name="birth_date", type="date", unique=false, nullable=false)
	*/
	protected $birthDate;
	
	/**
	* @ORM\ManyToOne(targetEntity="Gender")
	* @ORM\JoinColumn(name="gender_id", referencedColumnName="id")
	*/
	protected $gender;
	
	/**
	* @ORM\Column(name="is_agree", type="boolean", unique=false, nullable=false)
	*/
	protected $isAgree;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
    protected $google_id;

    /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
    protected $google_access_token;

    /**
     * @ORM\ManyToMany(targetEntity="Company")
     * @ORM\JoinTable(name="users_companies",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     * )
     */
    private $companies;

    /**
     * @ORM\ManyToMany(targetEntity="Project")
     * @ORM\JoinTable(name="users_projects",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")}
     * )
     */
    private $projects;

    public function __construct()
    {
        parent::__construct();
        parent::setUsername("empty");
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

    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);
        parent::setUsernameCanonical($emailCanonical);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Contact
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
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set isAgree
     *
     * @param boolean $isAgree
     * @return User
     */
    public function setIsAgree($isAgree)
    {
        $this->isAgree = $isAgree;

        return $this;
    }

    /**
     * Get isAgree
     *
     * @return boolean 
     */
    public function getIsAgree()
    {
        return $this->isAgree;
    }


    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set google_id
     *
     * @param string $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get google_id
     *
     * @return string 
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set google_access_token
     *
     * @param string $googleAccessToken
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get google_access_token
     *
     * @return string 
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Add companies
     *
     * @param \Clube\SiteBundle\Entity\Company $companies
     * @return User
     */
    public function addCompany(\Clube\SiteBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    /**
     * Remove companies
     *
     * @param \Clube\SiteBundle\Entity\Company $companies
     */
    public function removeCompany(\Clube\SiteBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Add projects
     *
     * @param \Clube\SiteBundle\Entity\Project $projects
     * @return User
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
}
