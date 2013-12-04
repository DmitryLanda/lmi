<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Model;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
interface ImageInterface
{
    const DEFAULT_IMAGE = 'no-image-available.png';

    /**
     * @return string
     */
    public function getAlternate();

    /**
     * @return string
     */
    public function getExtraLarge();

    /**
     * @return string
     */
    public function getLarge();

    /**
     * @return string
     */
    public function getMedium();

    /**
     * @return string
     */
    public function getSmall();

    /**
     * @return string
     */
    public function getThumb();

    /**
     * @return integer
     */
    public function getImageId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getSizeSequence();
}
