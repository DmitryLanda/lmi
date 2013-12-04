<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitry
 * Date: 25.11.13
 * Time: 23:04
 * To change this template use File | Settings | File Templates.
 */

namespace Lmi\Bundle\SchoolBundle\Service\YaFotki\Manager;


use Buzz\Browser;
use Buzz\Exception\ClientException;
use Buzz\Message\MessageInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Exception\YandexException;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\Album;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Model\AlbumInterface;
use Lmi\Bundle\SchoolBundle\Service\YaFotki\Serializer\SerializerInterface;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class AlbumManager implements AlbumManagerInterface
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
    private $urlPattern;

    /**
     * @var string
     */
    private $format;

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
        $this->urlPattern = $options['url_pattern']['album'];
        $this->format = $options['type'];
    }

    /**
     * @param integer $id
     * @return AlbumInterface|null
     * @throws YandexException
     */
    public function get($id)
    {
        $url = $this->buildUrl($id);

        try {
            $rawResponse = $this->connection->get($url);
        } catch (ClientException $e) {
            throw new YandexException('Unable to fetch album', null, $e);
        }

        $response = $this->processResponse($rawResponse);

        return new Album($response);
    }

    /**
     * @param string|null $name
     * @param AlbumInterface|null $parent
     * @throws YandexException
     */
    public function create($name = null, AlbumInterface $parent = null)
    {
        $albumUrl = $parent ? $parent->getSelfLink() : 'http://api-fotki.yandex.ru/api/users/lmi-images/albums/';
        $data = array();
        if ($name) {
            $data['title'] = $name;
            $headers = array(
                'Authorization' => sprintf('OAuth %s', $this->token),
                'Content-Type' => 'application/json; charset=utf-8; type=entry'
            );
            $content = json_encode($data);
            try {
                $rawResponse = $this->connection->post($albumUrl . '?format=json', $headers, $content);
                var_dump($albumUrl);
                var_dump($rawResponse); die();
            } catch (ClientException $e) {
                throw new YandexException('Unable to create album', null, $e);
            }

            $data = $this->processResponse($rawResponse);
        }

        return new Album($data);
    }

    /**
     * @param integer $id
     */
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
}
