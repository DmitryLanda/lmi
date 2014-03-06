<?php
/*
* Copyright © FarHeap Solutions
*
* For a full copyright notice, see the COPYRIGHT file.
*/

namespace Lmi\Bundle\SchoolBundle\Model\Manager;

use Lmi\Bundle\SchoolBundle\Model\Album;
use Lmi\Bundle\SchoolBundle\Model\Image;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class ImageManager extends EntityManager
{
    /**
     * @param string $title
     * @param string $content
     * @param Album|null $parentAlbum
     * @return Image
     */
    public function create($title, $content, Album $parentAlbum = null)
    {
        if ($parentAlbum) {
            $url = $parentAlbum->getPhotoCollectionUrl();
        } else {
            $url = $this->createUrl();
        }

        $response = $this->connection->sendPostRequest(
            $url,
            $content,
            'image/*'
        );
        if ($response) {
            $response->setTitle($title)
                ->setAccessLevel(Image::ACCESS_PUBLIC_LEVEL);

            $response = $this->connection->sendPutRequest(
                $this->createUrl($response->getId()),
                $response,
                'application/json; type=entry'
            );
        }

        return $response;
    }
}
