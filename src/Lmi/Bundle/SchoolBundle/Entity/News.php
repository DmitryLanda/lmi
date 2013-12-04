<?php

namespace Lmi\Bundle\SchoolBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Transliterator;

/**
 * News
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class News
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="last_update", type="datetime")
     */
    private $lastUpdate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="show_date", type="datetime")
     */
    private $showDate;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="images", type="text", nullable=true)
     */
    private $images;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=500)
     */
    private $identifier;

    public function __construct()
    {
        $date = new DateTime();
        $this->setCreated($date)
            ->refreshLastUpdateDate($date)
            ->setShowDate($date);
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param DateTime $created
     * @return News
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param DateTime $showDate
     * @return News
     */
    public function setShowDate($showDate)
    {
        $this->showDate = $showDate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getShowDate()
    {
        return $this->showDate;
    }

    /**
     * @param string $author
     * @return News
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return explode(',', $this->images);
    }

    /**
     * @param integer|string $image
     * @return News
     */
    public function addImage($image)
    {
        if (strpos($this->images, $image) === false) {
            $this->images .= ',' . $image;
        }

        return $this;
    }

    /**
     * @param integer|string $image
     * @return News
     */
    public function removeImage($image)
    {
        $this->images = str_replace($image, '', $this->images);

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getFirstImage()
    {
        if (!$this->images) {
            return null;
        }

        return $this->getImages()[0];
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function fillIdentifier()
    {
        $identifier = $this->getCreated()->format('m-d-Y') . '-' . $this->getTitle();
        $transliterator = Transliterator::create('Cyrillic-Latin');
        $this->identifier = str_replace(' ', '-', $transliterator->transliterate($identifier));
    }

    /**
     * @param string $identifier
     * @return News
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @ORM\PrePersist
     *
     * @return News
     */
    public function refreshLastUpdateDate()
    {
        $this->lastUpdate = new DateTime();

        return $this;
    }
}
