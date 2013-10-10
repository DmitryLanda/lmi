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
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Serializer\SerializerFactory;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class YandexFotkiService
{
    private $imageUrlPattern;

    private $albumUrlPattern;

    private $format;

    public $token = '4e57fda3beb640369ab6c45525ccf92f';

    private $connection;

    private $albumMap = array();

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->imageUrlPattern = $options['image_url_pattern'];
        $this->albumUrlPattern = $options['album_url_pattern'];
        $this->format = $options['format'];
        $this->albumMap = $options['album_map'];
        $this->albumMap['default'] = $options['default_album'];

        $client = new Curl();
        $this->connection = new Browser($client);
    }

    /**
     * @param integer $id
     * @return Image
     * @throws RuntimeException
     */
    public function getImage($id)
    {
        $url = $this->createWellFormedGetImageUrl($id);

        try {
            $response = $this->connection->get($url);
        } catch (ClientException $e) {
            //todo at least log it
            throw new RuntimeException('Could not fetch image from image storage', null, $e);
        }

        $data = $this->processResponse($response);

        return $this->createImageObject($data);
    }

    /**
     * @param File $file
     * @param string $type
     * @return Image
     * @throws RuntimeException
     */
    public function uploadImage(File $file, $type = '')
    {
        $albumId = $this->getAlbumIdFromType($type);

        try {
            $imageResource = $this->createImageResource($file);
            $imageString = $this->getImageContent($imageResource);

            $url = $this->createWellFormedAlbumUrl($albumId);
            $headers = array(
                'Authorization' => sprintf('OAuth %s', $this->token),
                'Content-Type' => $file->getMimeType()
            );

            $response = $this->connection->post($url, $headers, $imageString);
        } catch (ClientException $e) {
            //todo at least log it
            throw new RuntimeException('Could not upload image to image storage', null, $e);
        }
        $data = $this->processResponse($response);

        return $this->createImageObject($data);
    }

    public function removeImage()
    {

    }

    /**
     * @param integer $id
     * @return string
     */
    private function createWellFormedGetImageUrl($id)
    {
        return sprintf($this->imageUrlPattern . '?format=%s', $id, $this->format);
    }

    /**
     * @param MessageInterface $response
     * @return array
     */
    private function processResponse(MessageInterface $response)
    {
        $mimeType = $response->getHeader('Content-Type');
        if ($mimeType) {
            $delimiterPosition = strpos($mimeType, '/') + 1;
            $endPosition = strpos($mimeType, ';', $delimiterPosition);
            $type = trim(substr($mimeType, $delimiterPosition, ($endPosition - $delimiterPosition)));
        } else {
            $type = 'json';
        }

        $serializer = SerializerFactory::create($type);

        return $serializer->unserialize($response->getContent());
    }

    /**
     * @param mixed $data
     * @return Image
     */
    private function createImageObject($data)
    {
        return ImageFactory::create($data);
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

    /**
     * @param integer $albumId
     * @return string
     */
    private function createWellFormedAlbumUrl($albumId)
    {
        return sprintf($this->albumUrlPattern . '?format=%s', $albumId, $this->format);
    }

    /**
     * @param string $type
     * @return mixed
     */
    private function getAlbumIdFromType($type)
    {
        if (!array_key_exists($type, $this->albumMap)) {
            return $this->albumMap['default'];
        }

        return $this->albumMap[$type];
    }
}
