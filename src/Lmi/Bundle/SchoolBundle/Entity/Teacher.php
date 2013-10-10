<?php

namespace Lmi\Bundle\SchoolBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Teacher
 *
 * @ORM\Table
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Teacher
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
     * @ORM\Column(name="name", type="string", length=500)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="canonical_name", type="string", length=500)
     */
    private $canonicalName;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hideBirthdayYear", type="boolean")
     */
    private $hideBirthdayYear;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer")
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="stag", type="integer", nullable=true)
     */
    private $stag;

    /**
     * @var Contact[]
     *
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="user", cascade={"persist", "remove"})
     */
    private $contacts;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=500)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="education", type="integer")
     */
    private $education;

    /**
     * @var string
     *
     * @ORM\Column(name="biography", type="text", nullable=true)
     */
    private $biography;

    /**
     * @var Project[]
     *
     * @ORM\OneToMany(targetEntity="Project", mappedBy="user", cascade={"persist", "remove"})
     */
    private $projects;

    /**
     * @var Regard[]
     *
     * @ORM\OneToMany(targetEntity="Regard", mappedBy="user", cascade={"persist", "remove"})
     */
    private $regards;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     */
    private $photo;

    /**
     * @var Room
     *
     * @ORM\OneToOne(targetEntity="Room")
     * @ORM\JoinColumn(name="room", referencedColumnName="id", nullable=true)
     */
    private $room;


    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->regards = new ArrayCollection();
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
     * @return Teacher
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
     * Set subject
     *
     * @param string $subject
     * @return Teacher
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Teacher
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    
        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set hideBirthdayYear
     *
     * @param boolean $hideBirthdayYear
     * @return Teacher
     */
    public function setHideBirthdayYear($hideBirthdayYear)
    {
        $this->hideBirthdayYear = $hideBirthdayYear;
    
        return $this;
    }

    /**
     * Get hideBirthdayYear
     *
     * @return boolean 
     */
    public function getHideBirthdayYear()
    {
        return $this->hideBirthdayYear;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Teacher
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set stag
     *
     * @param integer $stag
     * @return Teacher
     */
    public function setStag($stag)
    {
        $this->stag = $stag;
    
        return $this;
    }

    /**
     * Get stag
     *
     * @return integer 
     */
    public function getStag()
    {
        return $this->stag;
    }

    /**
     * Set contacts
     *
     * @param array $contacts
     * @return Teacher
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
    
        return $this;
    }

    /**
     * Get contacts
     *
     * @return array 
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Contact $contact
     * @return Teacher
     */
    public function addContact(Contact $contact)
    {
        $this->contacts->add($contact);

        return $this;
    }

    /**
     * @param Contact $contact
     * @return Teacher
     */
    public function removeContact(Contact $contact)
    {
        $this->contacts->removeElement($contact);

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Teacher
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set education
     *
     * @param integer $education
     * @return Teacher
     */
    public function setEducation($education)
    {
        $this->education = $education;
    
        return $this;
    }

    /**
     * Get education
     *
     * @return integer 
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set biography
     *
     * @param string $biography
     * @return Teacher
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    
        return $this;
    }

    /**
     * Get biography
     *
     * @return string 
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set projects
     *
     * @param array $projects
     * @return Teacher
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;
    
        return $this;
    }

    /**
     * Get projects
     *
     * @return array 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param Project $project
     * @return Teacher
     */
    public function addProject(Project $project)
    {
        $this->projects->add($project);

        return $this;
    }

    /**
     * @param Project $project
     * @return Teacher
     */
    public function removeProject(Project $project)
    {
        $this->projects->removeElement($project);

        return $this;
    }


    /**
     * Set regards
     *
     * @param array $regards
     * @return Teacher
     */
    public function setRegards($regards)
    {
        $this->regards = $regards;
    
        return $this;
    }

    /**
     * Get regards
     *
     * @return array 
     */
    public function getRegards()
    {
        return $this->regards;
    }

    /**
     * @param Regard $regard
     * @return Teacher
     */
    public function addRegard(Regard $regard)
    {
        $this->regards->add($regard);

        return $this;
    }

    /**
     * @param Regard $regard
     * @return Teacher
     */
    public function removeRegard(Regard $regard)
    {
        $this->regards->removeElement($regard);

        return $this;
    }

    /**
     * @param Image $photo
     * @return Teacher
     */
    public function setPhoto($photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Image
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Teacher
     */
    public function fillCanonicalName()
    {
        $this->canonicalName = mb_convert_case(str_replace(' ', '-', $this->name), MB_CASE_LOWER, "UTF-8");

        return $this;
    }

    /**
     * @return string
     */
    public function getCanonicalName()
    {
        return $this->canonicalName;
    }

    /**
     * @param int $room
     * @return Teacher
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return int
     */
    public function getRoom()
    {
        return $this->room;
    }
}
