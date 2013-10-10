<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Image
{
    const DEFAULT_IMAGE = 'no-image-available.png';

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
     * @ORM\Column(name="image_id", type="integer")
     */
    private $imageId;

    /**
     * @var string
     *
     * @ORM\Column(name="xl", type="string", length=500)
     */
    private $extraLarge;

    /**
     * ~1024
     * @var string
     *
     * @ORM\Column(name="l", type="string", length=500)
     */
    private $large;

    /**
     * ~800
     * @var string
     *
     * @ORM\Column(name="m", type="string", length=500)
     */
    private $medium;

    /**
     * ~500
     * @var string
     *
     * @ORM\Column(name="s", type="string", length=500)
     */
    private $small;

    /**
     * ~150
     * @var string
     *
     * @ORM\Column(name="xs", type="string", length=500)
     */
    private $thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="alternate_link", type="string", length=500)
     */
    private $alternate;

    /**
     * @param string $alternate
     * @return Image
     */
    public function setAlternate($alternate)
    {
        $this->alternate = $alternate;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlternate()
    {
        return $this->alternate;
    }

    /**
     * @param string $extraLarge
     * @return Image
     */
    public function setExtraLarge($extraLarge)
    {
        $this->extraLarge = $extraLarge;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtraLarge()
    {
        return $this->extraLarge;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $large
     * @return Image
     */
    public function setLarge($large)
    {
        $this->large = $large;

        return $this;
    }

    /**
     * @return string
     */
    public function getLarge()
    {
        return $this->large;
    }

    /**
     * @param string $medium
     * @return Image
     */
    public function setMedium($medium)
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * @return string
     */
    public function getMedium()
    {
        return $this->medium;
    }

    /**
     * @param string $small
     * @return Image
     */
    public function setSmall($small)
    {
        $this->small = $small;

        return $this;
    }

    /**
     * @return string
     */
    public function getSmall()
    {
        return $this->small;
    }

    /**
     * @param string $thumb
     * @return Image
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * @param int $imageId
     * @return Image
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageId()
    {
        return $this->imageId;
    }

    public function getSizeSequence()
    {
        return array(
            'XL' => 'extraLarge',
            'L' => 'large',
            'M' => 'medium',
            'S' => 'small',
            'XS' => 'thumb'
        );
    }
}
