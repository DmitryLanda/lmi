<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory;

use Lmi\Bundle\SchoolBundle\Entity\Image;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
interface ImageFactoryInterface
{
    /**
     * @return Image
     */
    public static function create($data);
}
