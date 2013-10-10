<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki;

use Lmi\Bundle\SchoolBundle\Entity\Image;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory\ArrayImageFactory;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory\DefaultImageFactory;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory\ImageFactoryInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class ImageFactory implements ImageFactoryInterface
{
    /**
     * @param mixed $data
     * @return Image
     */
    public static function create($data = null)
    {
        $type = gettype($data);

        switch (strtolower($type)) {
            case 'array':
                return ArrayImageFactory::create($data);
            case 'null':
                return DefaultImageFactory::create($data);
        }

        //todo throw exception
        return null;
    }

    /**
     * @return Image
     */
    public static function createDefaultImage()
    {
        return self::create();
    }
}
