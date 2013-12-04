<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitry
 * Date: 26.11.13
 * Time: 0:10
 * To change this template use File | Settings | File Templates.
 */

namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Model;

use Symfony\Component\Config\FileLocator;

class Image implements ImageInterface
{
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
     * @param array $data
     */
    public function __construct(array $data = null)
    {
        if (!$data) {
            $this->fillWithDefaultValues();

            return;
        }

        $matches = array();
        preg_match('/\:(\d+)$/', $data['id'], $matches);

        //fill image samples
        $images = $data['img'];
        $keys = array_keys($images);
        foreach ($this->getSizeSequence() as $size => $property) {
            $key = array_search($size, $keys);
            if ($key === false) {
                $this->$property = $images['orig']['href'];
            } else {
                $this->$property = $images[$size]['href'];
            }
        }

        //fill other fields
        $this->id = $matches[1];
        $this->name = $data['title'];
        $this->alternativeLink = $data['links']['alternate'];
    }

    /**
     * @return string
     */
    public function getAlternate()
    {
        return $this->alternativeLink;
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
    public function getThumb()
    {
        return $this->thumbnail;
    }

    /**
     * @return integer
     */
    public function getImageId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getSizeSequence()
    {
        return array(
            'XL' => 'extraLarge',
            'L' => 'large',
            'M' => 'medium',
            'S' => 'small',
            'XS' => 'thumbnail'
        );
    }

    private function fillWithDefaultValues()
    {
        $fileLocator = new FileLocator(__DIR__ . '/../../../Resources/public/images/');
        $imagePath = $fileLocator->locate(ImageInterface::DEFAULT_IMAGE);

        $this->id = 0;
        $this->name = 'default';
        $this->alternativeLink = $imagePath;
        $this->extraLarge = $imagePath;
        $this->large = $imagePath;
        $this->medium = $imagePath;
        $this->small = $imagePath;
        $this->thumbnail = $imagePath;
    }
}
