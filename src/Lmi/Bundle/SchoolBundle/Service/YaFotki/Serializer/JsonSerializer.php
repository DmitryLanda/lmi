<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Serializer;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class JsonSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param string|null $data
     * @return array
     */
    public function unserialize($data = null)
    {
        $unserializedData = json_decode($data, true);
        if ($unserializedData === null) {
            //todo throw exception or just log it
            $unserializedData = null;
        }

        return $unserializedData;
    }
}
