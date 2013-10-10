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
interface SerializerInterface
{
    /**
     * @param string|null $data
     * @return mixed
     */
    public function unserialize($data = null);
}
