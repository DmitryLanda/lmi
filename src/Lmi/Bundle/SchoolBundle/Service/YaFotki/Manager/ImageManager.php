<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitry
 * Date: 25.11.13
 * Time: 23:25
 * To change this template use File | Settings | File Templates.
 */

namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Manager;

use Buzz\Browser;
use Buzz\Exception\ClientException;
use Buzz\Message\MessageInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\AlbumInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\Image;
use Symfony\Component\HttpFoundation\File\File;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\ImageFactory\ImageFactory;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Exception\YandexException;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\ImageInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Serializer\SerializerInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class ImageManager implements ImageManagerInterface
{
    /**
     * @var \Buzz\Browser
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $urlPattern;

    /**
     * @var string
     */
    private $token = '4e57fda3beb640369ab6c45525ccf92f';

    /**
     * @param Browser $connection
     * @param SerializerInterface $serializer
     * @param array $options
     */
    public function __construct(Browser $connection, SerializerInterface $serializer, array $options)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->format = $options['type'];
        $this->urlPattern = $options['url_pattern']['image'];
    }

    /**
     * @param integer $id
     * @return ImageInterface
     * @throws YandexException
     */
    public function get($id)
    {
        $url = $this->buildUrl($id);

        try {
            $rawResponse = $this->connection->get($url);
        } catch (ClientException $e) {
            throw new YandexException('Unable to fetch image', null, $e);
        }

        $response = $this->processResponse($rawResponse);

        return new Image($response);
    }

    /**
     * @param AlbumInterface $album
     * @return ImageInterface[]
     * @throws YandexException
     */
    public function getAllFromAlbum(AlbumInterface $album)
    {
        $images = array();
        $url = $album->getPhotoCollectionUrl();

        try {
            $rawResponse = $this->connection->get($url);
        } catch (ClientException $e) {
            throw new YandexException('Unable to fetch images', null, $e);
        }

        $response = $this->processResponse($rawResponse);

        foreach ($response['entries'] as $imageData) {
            $images[] = new Image($imageData);
        }

        return $images;
    }

    /**
     * @param File $file
     * @param string $albumUrl
     * @return ImageInterface
     * @throws YandexException
     */
    public function create(File $file = null, $albumUrl = null)
    {
        $albumUrl = $albumUrl ? $albumUrl : $this->urlPattern;
        $data = array();
        if ($file) {
            $headers = array(
                'Authorization' => sprintf('OAuth %s', $this->token),
                'Content-Type' => $file->getMimeType()
            );
            $content = $this->getImageContent($this->createImageResource($file));
            try {
                $rawResponse = $this->connection->post($albumUrl . '?format=json', $headers, $content);
            } catch (ClientException $e) {
                throw new YandexException('Unable to fetch album', null, $e);
            }

            $data = $this->processResponse($rawResponse);
        }

        return new Image($data);
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @param integer $id
     * @return string
     */
    private function buildUrl($id)
    {
        return sprintf($this->urlPattern . '%d/?format=%s', $id, $this->format);
    }

    /**
     * @param MessageInterface $response
     * @return array
     */
    private function processResponse(MessageInterface $response)
    {
        return $this->serializer->unserialize($response->getContent());
    }

    /**
     * @param File $file
     * @return array
     */
    private function createImageResource(File $file)
    {
        $mimeType = $file->getMimeType();
        $imageType = substr($mimeType, (strpos($mimeType, '/') + 1));
        switch ($imageType) {
            case 'png':
                $imageResource = imagecreatefrompng($file->getRealPath());
                break;
            default:
                $imageResource = imagecreatefromjpeg($file->getRealPath());
                break;
        }

        return $imageResource;
    }

    /**
     * @param $imageResource
     * @return string
     */
    public function getImageContent($imageResource)
    {
        ob_start();
        imagepng($imageResource);
        $imageString = ob_get_contents();
        ob_end_clean();

        return $imageString;
    }
}
