<?php
/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Lmi\Bundle\SchoolBundle\Service;

use Symfony\Component\Config\FileLocator;
use Lmi\Bundle\SchoolBundle\Model\Album;
use Lmi\Bundle\SchoolBundle\Model\Image;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class JsonMarshaller
{
    const ALBUM_TYPE = 'album';
    const PHOTO_TYPE = 'photo';
    const ALBUM_COLLECTION_TYPE = 'albums';
    const PHOTO_COLLECTION_TYPE = 'photos';

    /**
     * @param $object
     */
    public function marshall($object)
    {
        $data = array();
        if ($object instanceof Album) {
            $data['id'] = '0:1:2:3:album:' . $object->getId();
            $data['title'] = $object->getName();
            $links['alternate'] = $object->getAlternativeLink();
            $links['self'] = $object->getSelfLink();
            $links['photos'] = $object->getPhotoCollectionUrl();
            if ($object->hasParent()) {
                $links['album'] = '/album/' . $object->getParentId();
            }
            $data['links'] = $links;
            $data['edited'] = $object->getEdited()->format('Y-m-d H:i:s');
            $data['published'] = $object->getPublished()->format('Y-m-d H:i:s');
            $data['img']['XXS']['href'] = $object->getSmallThumbnail();
            $data['img']['S']['href'] = $object->getLargeThumbnail();
        } elseif ($object instanceof Image) {
            $data['id'] = '0:1:2:3:photo:' . $object->getId();
            $data['title'] = $object->getName();
            $links['alternate'] = $object->getAlternativeLink();
            $links['self'] = $object->getSelfLink();
            $links['album'] = $object->getAlbumLink();
            $data['links'] = $links;
            $data['edited'] = $object->getEdited()->format('Y-m-d H:i:s');
            $data['published'] = $object->getPublished()->format('Y-m-d H:i:s');
            $data['img']['orig']['href'] = $object->getOriginal();
            $data['img']['XL']['href'] = $object->getExtraLarge();
            $data['img']['L']['href'] = $object->getLarge();
            $data['img']['M']['href'] = $object->getMedium();
            $data['img']['S']['href'] = $object->getSmall();
            $data['img']['XS']['href'] = $object->getThumbnail();
            $data['access'] = $object->getAccessLevel();
        }

        return json_encode($data);
    }

    /**
     * @param null $jsonString
     * @return Album[]|Album|Image[]|Image
     */
    public function unmarshall($jsonString = null)
    {
        $data = json_decode($jsonString, true);
        //invalid json? looks silly
        if ($data === null) {
            //todo throw exception or just log it
        }

        $entityType = $this->getType($data['id']);
        switch ($entityType) {
            case self::ALBUM_TYPE:
                return $this->fillAlbum($data);
            case self::PHOTO_TYPE:
                return $this->fillPhoto($data);
            case self::ALBUM_COLLECTION_TYPE:
                return $this->fillAlbums($data['entries']);
            case self::PHOTO_COLLECTION_TYPE:
                return $this->fillPhotos($data['entries']);
        }
    }

    /**
     * @param string $id
     * @return string
     */
    private function getType($id)
    {
        $parts = explode(':', $id);

        return $parts[4];
    }

    /**
     * @param array $entity
     * @return Image
     */
    private function fillPhoto(array $entity)
    {
        $idParts = explode(':', $entity['id']);

        $origin = $entity['img']['orig']['href'];
        $extraLarge = array_key_exists('XL', $entity['img']) ? $entity['img']['XL']['href'] : $origin;
        $large = array_key_exists('L', $entity['img']) ? $entity['img']['L']['href'] : $origin;
        $medium = array_key_exists('M', $entity['img']) ? $entity['img']['M']['href'] : $origin;
        $small = array_key_exists('S', $entity['img']) ? $entity['img']['S']['href'] : $origin;
        $thumbnail = array_key_exists('XS', $entity['img']) ? $entity['img']['XS']['href'] : $origin;

        return new Image(
            $idParts[5],
            $entity['title'],
            $entity['links']['alternate'],
            $entity['links']['self'],
            $origin,
            $extraLarge,
            $large,
            $medium,
            $small,
            $thumbnail,
            $entity['links']['album'],
            $entity['edited'],
            $entity['published'],
            $entity['access']
        );
    }

    /**
     * @param array $entity
     * @return Album
     */
    private function fillAlbum(array $entity)
    {
        $idParts = explode(':', $entity['id']);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/public/images/');
        $defaultImage = $fileLocator->locate('no-image-available.png');

        if (!array_key_exists('img', $entity)) {
            $smallThumbnail = $largeThumbnail = $defaultImage;
        } else {
            $smallThumbnail = $entity['img']['XXS']['href'];
            $largeThumbnail = $entity['img']['S']['href'];
        }

        $parentId = null;
        if (array_key_exists('album', $entity['links'])) {
            $matches = array();
            preg_match('/album\/(\d+)/', $entity['links']['album'], $matches);
            $parentId = $matches[1];
        }

        return new Album(
            $idParts[5],
            $entity['title'],
            $entity['links']['alternate'],
            $entity['links']['self'],
            $smallThumbnail,
            $largeThumbnail,
            $entity['links']['photos'],
            $entity['edited'],
            $entity['published'],
            $parentId
        );
    }

    /**
     * @param array $entities
     * @return Image[]
     */
    private function fillPhotos(array $entities)
    {
        $photos = array();

        foreach ($entities as $entity) {
            $photos[] = $this->fillPhoto($entity);
        }

        return $photos;
    }

    /**
     * @param array $entities
     * @return Album[]
     */
    private function fillAlbums(array $entities)
    {
        $albums = array();

        foreach ($entities as $entity) {
            $albums[] = $this->fillAlbum($entity);
        }

        return $albums;
    }
}
