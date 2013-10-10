<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory;

use Lmi\Bundle\SchoolBundle\Entity\Image;
use ReflectionObject;
use Symfony\Component\Config\FileLocator;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class DefaultImageFactory implements ImageFactoryInterface
{
    public static function create($data)
    {
        $fileLocator = new FileLocator(__DIR__ . '/../../../Resources/public/images/');
        $imagePath = $fileLocator->locate(Image::DEFAULT_IMAGE);

        $image = new Image();
        $reflection = new ReflectionObject($image);

        $data = array(
            'imageId' => 0,
            'alternate' => $imagePath,
            'extraLarge' => $imagePath,
            'large' => $imagePath,
            'medium' => $imagePath,
            'small' => $imagePath,
            'thumb' => $imagePath
        );

        foreach ($data as $key => $value) {
            $reflectedProperty = $reflection->getProperty($key);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($image, $value);
            $reflectedProperty->setAccessible(false);
        }

        return $image;
    }
}
