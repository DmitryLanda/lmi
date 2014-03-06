<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitry
 * Date: 26.11.13
 * Time: 0:11
 * To change this template use File | Settings | File Templates.
 */

namespace Lmi\Bundle\SchoolBundle\Model;

use DateTime;

class Album
{
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
    private $alternativeLink;
    /**
     * @var string
     */
    private $selfLink;
    /**
     * @var string
     */
    private $smallThumbnail;
    /**
     * @var string
     */
    private $largeThumbnail;
    /**
     * @var string
     */
    private $photoCollectionUrl;

    /**
     * @var DateTime
     */
    private $edited;

    /**
     * @var DateTime
     */
    private $published;

    /**
     * @var integer|null
     */
    private $parentId;

    /**
     * @param string|integer $id
     * @param string $title
     * @param string $alternativeLink
     * @param string $selfLink
     * @param string $smallThumbnail
     * @param string $largeThumbnail
     * @param string $photoCollectionLink
     * @param string|null $edited
     * @param string|null $published
     * @param null|integer $parentId
     */
    public function __construct($id, $title, $alternativeLink, $selfLink, $smallThumbnail,
                                $largeThumbnail, $photoCollectionLink, $edited = null,
                                $published = null, $parentId = null)
    {
        $this->id = $id;
        $this->name = $title;
        $this->alternativeLink = $alternativeLink;
        $this->selfLink = $selfLink;
        $this->smallThumbnail = $smallThumbnail;
        $this->largeThumbnail = $largeThumbnail;
        $this->edited = $edited ? new DateTime($edited) : new DateTime();
        $this->published = $published ? new DateTime($published) : new DateTime();
        $this->photoCollectionUrl = $photoCollectionLink;
        $this->parentId = $parentId;
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
    public function getAlternativeLink()
    {
        return $this->alternativeLink;
    }

    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->selfLink;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPhotoCollectionUrl()
    {
        return $this->photoCollectionUrl;
    }

    /**
     * @return DateTime
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * @return string
     */
    public function getLargeThumbnail()
    {
        return $this->largeThumbnail;
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
    public function getSmallThumbnail()
    {
        return $this->smallThumbnail;
    }

    /**
     * @return boolean
     */
    public function hasParent()
    {
        return $this->parentId != null;
    }

    /**
     * @return integer|null
     */
    public function getParentId()
    {
        return $this->parentId;
    }
}
