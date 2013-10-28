<?php

namespace Lmi\Bundle\SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Room
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
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var Image
     *
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="thumbnail", referencedColumnName="id", nullable=true)
    private $thumbnail;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var Teacher
     *
     * @ORM\OneToOne(targetEntity="Teacher")
     * @ORM\JoinColumn(name="responsible", referencedColumnName="id", nullable=false)
     */
    private $responsible;

    /**
     * @var Teacher
     *
     * @ORM\OneToOne(targetEntity="Teacher")
     * @ORM\JoinColumn(name="firesafetyresponsible", referencedColumnName="id", nullable=false)
     */
    private $fireSafetyResponsible;


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
     * Set number
     *
     * @param integer $number
     * @return Room
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param Image $thumbnail
     * @return Room
     */
    public function setThumbnail(Image $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    
        return $this;
    }

    /**
     * @return Image
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Room
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set responsible
     *
     * @param integer $responsible
     * @return Room
     */
    public function setResponsible($responsible)
    {
        $this->responsible = $responsible;
    
        return $this;
    }

    /**
     * Get responsible
     *
     * @return integer 
     */
    public function getResponsible()
    {
        return $this->responsible;
    }

    /**
     * Set fireSafetyResponsible
     *
     * @param integer $fireSafetyResponsible
     * @return Room
     */
    public function setFireSafetyResponsible($fireSafetyResponsible)
    {
        $this->fireSafetyResponsible = $fireSafetyResponsible;
    
        return $this;
    }

    /**
     * Get fireSafetyResponsible
     *
     * @return integer 
     */
    public function getFireSafetyResponsible()
    {
        return $this->fireSafetyResponsible;
    }
}
