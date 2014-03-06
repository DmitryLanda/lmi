<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitry
 * Date: 26.11.13
 * Time: 0:10
 * To change this template use File | Settings | File Templates.
 */

namespace Lmi\Bundle\SchoolBundle\Model;

use DateTime;

class Image
{
    const ACCESS_PUBLIC_LEVEL = 'public';
    const ACCESS_PRIVATE_LEVEL = 'private';

    /**
     * @var string
     */
    private $alternativeLink;

    /**
     * @var string
     */
    private $extraLarge;

    /**
     * @var string
     */
    private $large;

    /**
     * @var string
     */
    private $medium;

    /**
     * @var string
     */
    private $small;

    /**
     * @var string
     */
    private $thumbnail;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $selfLink;

    /**
     * @var string
     */
    private $albumLink;

    /**
     * @var DateTime
     */
    private $edited;

    /**
     * @var DateTime
     */
    private $published;

    /**
     * @var string
     */
    private $original;

    /**
     * @var string
     */
    private $accessLevel;

    /**
     * @param integer $id
     * @param string $title
     * @param string $alternativeLink
     * @param string $selfLink
     * @param string $original
     * @param string $extraLarge
     * @param string $large
     * @param string $medium
     * @param string $small
     * @param string $thumbnail
     * @param string $albumLink
     * @param string|null $edited
     * @param string|null $published
     * @param string $access
     */
    public function __construct($id, $title, $alternativeLink, $selfLink, $original, $extraLarge, $large,
                                $medium, $small, $thumbnail, $albumLink, $edited = null, $published = null,
                                $access = self::ACCESS_PUBLIC_LEVEL)
    {
        $this->id = $id;
        $this->name = $title;
        $this->alternativeLink = $alternativeLink;
        $this->selfLink = $selfLink;
        $this->original = $original;
        $this->extraLarge = $extraLarge;
        $this->large = $large;
        $this->medium = $medium;
        $this->small = $small;
        $this->thumbnail = $thumbnail;
        $this->edited = $edited ? new DateTime($edited) : new DateTime();
        $this->published = $published ? new DateTime($published) : new DateTime();
        $this->albumLink = $albumLink;
        $this->accessLevel = $access;
    }

    /**
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * @return string
     */
    public function getExtraLarge()
    {
        return $this->extraLarge;
    }

    /**
     * @return string
     */
    public function getLarge()
    {
        return $this->large;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @return string
     */
    public function getSmall()
    {
        return $this->small;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAlbumLink()
    {
        return $this->albumLink;
    }

    /**
     * @return string
     */
    public function getAlternativeLink()
    {
        return $this->alternativeLink;
    }

    /**
     * @return DateTime
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->selfLink;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     * @param string $accessLevel
     * @return Image
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * @param string $name
     * @return Image
     */
    public function setTitle($name)
    {
        $this->name = $name;

        return $this;
    }
}
