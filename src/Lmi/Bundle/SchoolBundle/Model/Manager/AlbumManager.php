<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

namespace Lmi\Bundle\SchoolBundle\Model\Manager;

use Lmi\Bundle\SchoolBundle\Model\Album;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class AlbumManager extends EntityManager
{
    /**
     * @param string $title
     * @param Album|null $parent
     * @return Album
     */
    public function create($title, Album $parent = null)
    {
        $data['title'] = $title;
        if ($parent) {
            $data['links']['album'] = $parent->getSelfLink();
        }

        $response = $this->connection->sendPostRequest(
            $this->createUrl(),
            json_encode($data),
            'application/json; type=entry'
        );

        return $response;
    }
}
