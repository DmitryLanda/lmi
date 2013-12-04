<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/

namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Manager;

use Lmi\Bundle\SchoolBundle\Service\YaFotki\Exception\YandexException;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\AlbumInterface;

interface AlbumManagerInterface
{
    /**
     * @param integer $id
     * @return AlbumInterface
     */
    public function get($id);

    /**
     * @param string|null $name
     * @param AlbumInterface|null $parent
     * @throws YandexException
     */
    public function create($name = null, AlbumInterface $parent = null);

    /**
     * @param integer $id
     */
    public function remove($id);
}
