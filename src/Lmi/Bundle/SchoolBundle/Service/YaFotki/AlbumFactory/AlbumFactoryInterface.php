<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\AlbumFactory;

use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\AlbumInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
interface AlbumFactoryInterface
{
    /**
     * @param array|null $data
     * @return AlbumInterface
     */
    public static function create(array $data = null);
}
