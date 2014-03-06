<?php
/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Lmi\Bundle\SchoolBundle\Service;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class GaufretteAlbumAdapter extends AbstractGaufretteEntityAdapter
{
    /**
     * @return boolean
     */
    protected function isAlbum()
    {
        return true;
    }
}
