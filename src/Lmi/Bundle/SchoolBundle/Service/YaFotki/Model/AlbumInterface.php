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
interface AlbumInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getAlternativeLink();

    /**
     * @return string
     */
    public function getSelfLink();

    /**
     * @return string
     */
    public function getSmallThumb();

    /**
     * @return string
     */
    public function getLargeThumb();

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getPhotoCollectionUrl();
}
