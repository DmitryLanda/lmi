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
use Psr\Log\LoggerInterface;

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
    private $albumUrlPattern;

    /**
     * @var string
     */
    private $albumCollectionUrlPattern;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $token = '4e57fda3beb640369ab6c45525ccf92f';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     * @param Browser $connection
     * @param SerializerInterface $serializer
     * @param array $options
     */
    public function __construct(LoggerInterface $logger, Browser $connection, SerializerInterface $serializer, array $options)
    {
        $this->logger = $logger;
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->albumUrlPattern = $options['url_pattern']['album'];
        $this->albumCollectionUrlPattern = $options['url_pattern']['album_collection'];
        $this->format = $options['type'];
    }

    /**
     * @param integer $id
     * @return AlbumInterface|null
     */
    public function get($id)
    {
        $url = $this->buildUrl($id);

        try {
            $rawResponse = $this->connection->get($url);
        } catch (ClientException $e) {
            $this->logger->error('Unuble to fetch user album ' . $id);
            $this->logger->error($e->getMessage());

            return new Album();
        }

        $response = $this->processResponse($rawResponse);

        return new Album($response);
    }

    /**
     * @param string|null $name
     * @param AlbumInterface|null $parent
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
            } catch (ClientException $e) {
                $this->logger->error('Unuble to create album ' . $name);
                $this->logger->error($e->getMessage());

                return new Album();
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
     * {@inheritdoc}
     *
     * @return AlbumInterface[]
     */
    public function getAll()
    {
        $url = $this->buildUrl();

        try {
            $rawResponse = $this->connection->get($url);
        } catch (ClientException $e) {
            $this->logger->error('Unuble to fetch user albums');
            $this->logger->error($e->getMessage());

            return array();
        }

        $response = $this->processResponse($rawResponse);

        $albums = array();

        foreach ($response['entries'] as $albumData) {
            $albums[] = new Album($albumData);
        }

        return $albums;
    }

    /**
     * @param integer|null $id
     * @return string
     */
    private function buildUrl($id = null)
    {
        if ($id) {
            $url = sprintf($this->albumUrlPattern . '%d/?format=%s', $id, $this->format);
        } else {
            $url = sprintf($this->albumCollectionUrlPattern . '?format=%s', $this->format);
        }

        return $url;
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
