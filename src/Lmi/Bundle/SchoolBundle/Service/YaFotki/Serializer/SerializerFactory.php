<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Serializer;

use RuntimeException;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class SerializerFactory
{
    /**
     * @param string $type
     * @return JsonSerializer|null
     */
    public static function create($type)
    {
        switch ($type) {
            case 'json':
                return new JsonSerializer();
        }

        throw new RuntimeException(sprintf('Content type "%s" is not supported', $type));
    }
}
