<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitry
 * Date: 26.11.13
 * Time: 0:11
 * To change this template use File | Settings | File Templates.
 */

namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Model;

use Symfony\Component\Config\FileLocator;

class Album implements AlbumInterface
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
     * @param array|null $data
     */
    public function __construct(array $data = null)
    {
        if (!$data) {
            $this->fillWithDefaultValues();

            return;
        }
        $matches = array();
        preg_match('/\:(\d+)$/', $data['id'], $matches);

        $this->id = (integer) $matches[1];
        $this->name = $data['title'];
        $this->alternativeLink = $data['links']['alternate'];
        $selfLink = $data['links']['self'];
        $this->selfLink = substr($selfLink, 0, strpos($selfLink, '?'));
        $photoCollectionLink = $data['links']['photos'];
        $this->photoCollectionUrl = substr($photoCollectionLink, 0, strpos($photoCollectionLink, '?'));
        if (isset($data['img'])) {
            $this->largeThumbnail = $data['img']['S']['href'];
            $this->smallThumbnail = $data['img']['XXS']['href'];
        }
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
     * @return string
     */
    public function getSmallThumb()
    {
        return $this->smallThumbnail;
    }

    /**
     * @return string
     */
    public function getLargeThumb()
    {
        return $this->largeThumbnail;
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

    private function fillWithDefaultValues()
    {
        $fileLocator = new FileLocator(__DIR__ . '/../../../Resources/public/images/');
        $imagePath = $fileLocator->locate(ImageInterface::DEFAULT_IMAGE);

        $this->id = 0;
        $this->name = 'default';
        $this->alternativeLink = $imagePath;
        $this->selfLink = $imagePath;
        $this->photoCollectionUrl = '';
        $this->largeThumbnail = $imagePath;
        $this->smallThumbnail = $imagePath;
    }
}
