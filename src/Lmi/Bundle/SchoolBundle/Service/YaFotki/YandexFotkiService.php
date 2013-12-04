<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/
namespace Lmi\Bundle\SchoolBundle\Service\YaFotki;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Exception\ClientException;
use Buzz\Message\MessageInterface;
use Lmi\Bundle\SchoolBundle\Entity\Image;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Exception\YandexException;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory\ImageFactory;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Manager\AlbumManagerInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Manager\ImageManagerInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\AlbumInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\ImageInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Serializer\SerializerFactory;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class YandexFotkiService
{
//    public $token = '4e57fda3beb640369ab6c45525ccf92f';

    /**
     * @var ImageManagerInterface
     */
    private $imageManager;

    /**
     * @var AlbumManagerInterface
     */
    private $albumManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ImageManagerInterface $imageManager,
        AlbumManagerInterface $albumManager,
        LoggerInterface $logger
    )
    {
        $this->imageManager = $imageManager;
        $this->albumManager = $albumManager;
        $this->logger = $logger;
    }

    /**
     * @param File $file
     * @param integer $albumId
     * @return ImageInterface
     * @throws YandexException
     */
    public function addImage(File $file, $album)
    {
        try {
            if (is_string($album)) {
                $album = $this->albumManager->create($album);
            } elseif (is_integer($album)) {
                $album = $this->albumManager->get($album);
            } elseif (!$album instanceof AlbumInterface) {
                throw new YandexException('Invalid album passed. It could be a string, integer or an instance of AlbumInterface');
            }

            if (!$album) {
                throw new YandexException('Album not found');
            }

            $image = $this->imageManager->create($file, $album);
        } catch (YandexException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $image = new Image();
        }

        return $image;
    }

    /**
     * @param integer $id
     * @return ImageInterface
     * @throws YandexException
     */
    public function getImage($id)
    {
        try {
            $image = $this->imageManager->get($id);
            if (!$image) {
                throw new YandexException(sprintf('Could not get image %d', $id));
            }
        } catch (YandexException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $image = new Image();
        }

        return $image;
    }

    /**
     * @return ImageInterface
     * @throws YandexException
     */
    public function getImages(AlbumInterface $album)
    {
        $images = array();
        try {
            $images = $this->imageManager->getAllFromAlbum($album);
        } catch (YandexException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
        }

        return $images;
    }

    /**
     * @param integer $id
     * @return int|AlbumInterface|null
     */
    public function getAlbum($id)
    {
        try {
            $album = $this->albumManager->get($id);
        } catch (YandexException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $album = $this->albumManager->create();
        }

        return $album;
    }

    /**
     * @return AlbumInterface[]
     */
    public function getAlbums()
    {
        try {
            $albums = $this->albumManager->getAll();
        } catch (YandexException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $albums[] = $this->albumManager->create();
        }

        return $albums;
    }

    /**
     * @param string $name
     * @param AlbumInterface $parent
     * @return AlbumInterface|null
     */
    public function addAlbum($name, AlbumInterface $parent = null)
    {
        try {
            $album = $this->albumManager->create($name, $parent);
        } catch (YandexException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());

            $album = $this->albumManager->create();
        }

        return $album;
    }

    /**
     * @param integer $id
     * @return Image
     * @throws RuntimeException
     */
//    public function getImage($id)
//    {
//        $url = $this->createWellFormedGetImageUrl($id);
//
//        try {
//            $response = $this->connection->get($url);
//        } catch (ClientException $e) {
//            //todo at least log it
//            throw new RuntimeException('Could not fetch image from image storage', null, $e);
//        }
//
//        $data = $this->processResponse($response);
//
//        return $this->createImageObject($data);
//    }

    /**
     * @param File $file
     * @param string $type
     * @return Image
     * @throws RuntimeException
     */
//    public function uploadImage(File $file, $type = '')
//    {
//        $albumId = $this->getAlbumIdFromType($type);
//
//        try {
//            $imageResource = $this->createImageResource($file);
//            $imageString = $this->getImageContent($imageResource);
//
//            $url = $this->createWellFormedAlbumUrl($albumId);
//            $headers = array(
//                'Authorization' => sprintf('OAuth %s', $this->token),
//                'Content-Type' => $file->getMimeType()
//            );
//
//            $response = $this->connection->post($url, $headers, $imageString);
//        } catch (ClientException $e) {
//            //todo at least log it
//            throw new RuntimeException('Could not upload image to image storage', null, $e);
//        }
//        $data = $this->processResponse($response);
//
//        return $this->createImageObject($data);
//    }

//    public function removeImage()
//    {
//
//    }

    /**
     * @param integer $id
     * @return string
     */
//    private function createWellFormedGetImageUrl($id)
//    {
//        return sprintf($this->imageUrlPattern . '?format=%s', $id, $this->format);
//    }

    /**
     * @param MessageInterface $response
     * @return array
     */
//    private function processResponse(MessageInterface $response)
//    {
//        $mimeType = $response->getHeader('Content-Type');
//        if ($mimeType) {
//            $delimiterPosition = strpos($mimeType, '/') + 1;
//            $endPosition = strpos($mimeType, ';', $delimiterPosition);
//            $type = trim(substr($mimeType, $delimiterPosition, ($endPosition - $delimiterPosition)));
//        } else {
//            $type = 'json';
//        }
//
//        $serializer = SerializerFactory::create($type);
//
//        return $serializer->unserialize($response->getContent());
//    }

    /**
     * @param mixed $data
     * @return Image
     */
//    private function createImageObject($data)
//    {
//        return ImageFactory::create($data);
//    }

    /**
     * @param File $file
     * @return array
     */
//    private function createImageResource(File $file)
//    {
//        $mimeType = $file->getMimeType();
//        $imageType = substr($mimeType, (strpos($mimeType, '/') + 1));
//        switch ($imageType) {
//            case 'png':
//                $imageResource = imagecreatefrompng($file->getRealPath());
//                break;
//            default:
//                $imageResource = imagecreatefromjpeg($file->getRealPath());
//                break;
//        }
//
//        return $imageResource;
//    }

    /**
     * @param $imageResource
     * @return string
     */
//    public function getImageContent($imageResource)
//    {
//        ob_start();
//        imagepng($imageResource);
//        $imageString = ob_get_contents();
//        ob_end_clean();
//
//        return $imageString;
//    }

    /**
     * @param integer $albumId
     * @return string
     */
//    private function createWellFormedAlbumUrl($albumId)
//    {
//        return sprintf($this->albumUrlPattern . '?format=%s', $albumId, $this->format);
//    }

    /**
     * @param string $type
     * @return mixed
     */
//    private function getAlbumIdFromType($type)
//    {
//        if (!array_key_exists($type, $this->albumMap)) {
//            return $this->albumMap['default'];
//        }
//
//        return $this->albumMap[$type];
//    }
}
