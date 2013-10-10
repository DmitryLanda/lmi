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

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class ArrayImageFactory implements ImageFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param array $data
     * @return Image
     */
    public static function create($data)
    {
        $image = new Image();
        $reflection = new ReflectionObject($image);
    
        $matches = array();
        preg_match('/\:(\d+)$/', $data['id'], $matches);

        //fill image samples
        $images = $data['img'];
        $keys = array_keys($images);
        foreach ($image->getSizeSequence() as $size => $property) {
            $reflectedProperty = $reflection->getProperty($property);
            $reflectedProperty->setAccessible(true);

            $key = array_search($size, $keys);
            if ($key === false) {
                $reflectedProperty->setValue($image, $images['orig']['href']);
            } else {
                $reflectedProperty->setValue($image, $images[$size]['href']);
            }

            $reflectedProperty->setAccessible(false);
        }

        //fill other fields
        $image->setImageId($matches[1]);
        $image->setAlternate($data['links']['alternate']);

        return $image;
    }
}
