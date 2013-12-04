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
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\ImageInterface;
use Symfony\Component\HttpFoundation\File\File;

interface ImageManagerInterface
{
    /**
     * @param integer $id
     * @return ImageInterface
     */
    public function get($id);

    /**
     * @param AlbumInterface $album
     * @return ImageInterface[]
     * @throws YandexException
     */
    public function getAllFromAlbum(AlbumInterface $album);

    /**
     * @param File $file
     * @param string $albumUrl
     * @return ImageInterface
     * @throws YandexException
     */
    public function create(File $file = null, $albumUrl = null);

    /**
     * @param integer $id
     */
    public function remove($id);
}
