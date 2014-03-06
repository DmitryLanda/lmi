<?php
/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Lmi\Bundle\SchoolBundle\Service;

use Buzz\Browser;
use Buzz\Exception\ClientException;
use Buzz\Message\MessageInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class Connection
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Browser
     */
    private $connection;
    /**
     * @var JsonMarshaller
     */
    private $marshaller;
    /**
     * @var string
     */
    private $token;

    /**
     * @param LoggerInterface $logger
     * @param Browser $connection
     * @param JsonMarshaller $marshaller
     * @param string $token
     */
    public function __construct(LoggerInterface $logger, Browser $connection,
                                JsonMarshaller $marshaller, $token)
    {
        $this->logger = $logger;
        $this->connection = $connection;
        $this->marshaller = $marshaller;
        $this->token = $token;
    }

    /**
     * @param string $url
     * @param boolean $needsAuthorization
     * @return MessageInterface|null
     */
    public function sendGetRequest($url, $needsAuthorization = false)
    {
        $headers = $needsAuthorization ? $this->getAuthHeader() : array();

        try {
            $rawResponse = $this->connection->get($url, $headers);
        } catch (ClientException $e) {
            $this->logger->error('Unable to fetch requested data');
            $this->logger->error('Requested url: ' . $url);
            $this->logger->error($e->getMessage());

            return null;
        }

        return $rawResponse;//$this->processResponse($rawResponse);

    }

    /**
     * @param string $url
     * @param string $content
     * @param string $mimeType
     * @return MessageInterface|null
     */
    public function sendPostRequest($url, $content, $mimeType)
    {
        $headers = array_merge(
            $this->getAuthHeader(),
            $this->getContentHeader($mimeType)
        );

        try {
            $rawResponse = $this->connection->post($url, $headers, $content);
            $this->logger->error($rawResponse);
        } catch (ClientException $e) {
            $this->logger->error('Unable to post data');
            $this->logger->error('Target url: ' . $url);
            $this->logger->error($e->getMessage());

            return null;
        }

        return $rawResponse;//$this->processResponse($rawResponse);
    }

    /**
     * @param string $url
     * @param string $content
     * @param string $mimeType
     * @return MessageInterface|null
     */
    public function sendPutRequest($url, $content, $mimeType)
    {
        $headers = array_merge(
            $this->getAuthHeader(),
            $this->getContentHeader($mimeType)
        );

        try {
//            $content = $this->marshaller->marshall($content);
            $rawResponse = $this->connection->put($url, $headers, $content);
        } catch (ClientException $e) {
            $this->logger->error('Unable to update data');
            $this->logger->error('Target url: ' . $url);
            $this->logger->error($e->getMessage());

            return null;
        }

        return $rawResponse;//$this->processResponse($rawResponse);
    }

    /**
     * @param string $url
     * @return boolean
     */
    public function sendDeleteRequest($url)
    {
        $headers = array_merge(
            $this->getAuthHeader()
        );

        try {
            $this->connection->delete($url, $headers);
        } catch (ClientException $e) {
            $this->logger->error('Unable to remove data');
            $this->logger->error('Target url: ' . $url);
            $this->logger->error($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    private function getAuthHeader()
    {
        return array('Authorization' => sprintf('OAuth %s', $this->token));
    }

    /**
     * @param $mimeType
     * @return array
     */
    private function getContentHeader($mimeType)
    {
        return array('Content-Type' => $mimeType);
    }

    /**
     * @param MessageInterface $response
     * @return array
     */
    private function processResponse(MessageInterface $response)
    {
        return $this->marshaller->unmarshall($response->getContent());
    }
}
