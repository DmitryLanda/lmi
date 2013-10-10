<?php

namespace Lmi\Bundle\SchoolBundle\Form\Map;

use DateTime;
use Lmi\Bundle\SchoolBundle\Entity\Contact;
use Lmi\Bundle\SchoolBundle\Entity\Image;
use Lmi\Bundle\SchoolBundle\Entity\Project;
use Lmi\Bundle\SchoolBundle\Entity\Teacher;
use Lmi\Bundle\SchoolBundle\Entity\Regard;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TeacherMap
{
    private $name;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var DateTime
     */
    private $birthday;

    /**
     * @var boolean
     */
    private $hideBirthdayYear;

    /**
     * @var integer
     */
    private $category;

    /**
     * @var integer
     */
    private $stag;

    /**
     * @var array
     */
    private $contacts;

    /**
     * @var string
     */
    private $email;

    /**
     * @var integer
     */
    private $education;

    /**
     * @var string
     */
    private $biography;

    /**
     * @var array
     */
    private $projects;

    /**
     * @var array
     */
    private $regards;

    /**
     * @var Image|UploadedFile|File
     */
    private $photo;

    /**
     * @var integer
     */
    private $room;

    /**
     * @param string $biography
     * @return TeacherMap
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return string
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param DateTime $birthday
     * @return TeacherMap
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param integer $category
     * @return TeacherMap
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Contact $contacts
     * @return TeacherMap
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return Contact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param integer $education
     * @return TeacherMap
     */
    public function setEducation($education)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * @return integer
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param string $email
     * @return TeacherMap
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param boolean $hideBirthdayYear
     * @return TeacherMap
     */
    public function setHideBirthdayYear($hideBirthdayYear)
    {
        $this->hideBirthdayYear = $hideBirthdayYear;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getHideBirthdayYear()
    {
        return $this->hideBirthdayYear;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Image|UploadedFile|File $photo
     * @return TeacherMap
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Image|UploadedFile|File
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param array $projects
     * @return TeacherMap
     */
    public function setProjects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * @return Project[]
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @param array $regards
     * @return TeacherMap
     */
    public function setRegards($regards)
    {
        $this->regards = $regards;

        return $this;
    }

    /**
     * @return Regard[]
     */
    public function getRegards()
    {
        return $this->regards;
    }

    /**
     * @param integer $stag
     * @return TeacherMap
     */
    public function setStag($stag)
    {
        $this->stag = $stag;

        return $this;
    }

    /**
     * @return integer
     */
    public function getStag()
    {
        return $this->stag;
    }

    /**
     * @param string $subject
     * @return TeacherMap
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param int $room
     * @return TeacherMap
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

    /**
     * @param Teacher $teacher
     * @return TeacherMap
     */
    public function fillFromModel(Teacher $teacher)
    {
        $this->setBiography($teacher->getBiography())
            ->setBirthday($teacher->getBirthday())
            ->setCategory($teacher->getCategory())
            ->setContacts($teacher->getContacts())
            ->setEducation($teacher->getEducation())
            ->setEmail($teacher->getEmail())
            ->setHideBirthdayYear($teacher->getHideBirthdayYear())
            ->setName($teacher->getName())
            ->setRoom($teacher->getRoom())
            ->setProjects($teacher->getProjects())
            ->setRegards($teacher->getRegards())
            ->setStag($teacher->getStag())
            ->setSubject($teacher->getSubject());

        return $this;
    }

    /**
     * @param Teacher $teacher
     * @return TeacherMap
     */
    public function updateModel(Teacher $teacher)
    {
        foreach ($this->getContacts() as $contact) {
            $contact->setUser($teacher);
        }

        foreach ($this->getProjects() as $project) {
            $project->setUser($teacher);
        }

        foreach ($this->getRegards() as $regard) {
            $regard->setUser($teacher);
        }

        $teacher->setBiography($this->getBiography())
            ->setBirthday($this->getBirthday())
            ->setCategory($this->getCategory())
            ->setContacts($this->getContacts())
            ->setEducation($this->getEducation())
            ->setEmail($this->getEmail())
            ->setHideBirthdayYear($this->getHideBirthdayYear())
            ->setName($this->getName())
            ->setRoom($this->getRoom())
            ->setProjects($this->getProjects())
            ->setRegards($this->getRegards())
            ->setStag($this->getStag())
            ->setSubject($this->getSubject());

        return $this;
    }
}
