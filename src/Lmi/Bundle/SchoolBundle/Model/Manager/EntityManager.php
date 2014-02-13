<?php
/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Lmi\Bundle\SchoolBundle\Model\Manager;

use BadMethodCallException;
use Lmi\Bundle\SchoolBundle\Model\Album;
use Lmi\Bundle\SchoolBundle\Model\Image;
use Lmi\Bundle\SchoolBundle\Service\Connection;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
class EntityManager
{
    /**
     * @var Connection
     */
    protected $connection;
    /**
     * @var
     */
    private $singleEntityPattern;
    /**
     * @var
     */
    private $defaultCollectionPattern;

    /**
     * @param Connection $connection
     * @param string $singleEntityPattern
     * @param string $defaultCollectionPattern
     */
    public function __construct(Connection $connection, $singleEntityPattern, $defaultCollectionPattern)
    {
        $this->connection = $connection;
        $this->singleEntityPattern = $singleEntityPattern;
        $this->defaultCollectionPattern = $defaultCollectionPattern;
    }

    /**
     * @param integer $id
     * @return Album|Image|null
     */
    public function getOneById($id)
    {
        $url = $this->createUrl($id);
        
        return $this->connection->sendGetRequest($url);
    }

    /**
     * @return Album[]|Image[]
     */
    public function getAll()
    {
        $url = $this->createUrl();

        $albums = $this->connection->sendGetRequest($url, true);
        $albums = $albums ?: array();

        return $albums;
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function remove($id)
    {
        $url = $this->createUrl($id);

        return $this->connection->sendDeleteRequest($url);
    }

    /**
     * @param integer|null $id
     * @return string
     */
    protected function createUrl($id = null)
    {
        if ($id) {
            return str_replace(':id', $id, $this->singleEntityPattern);
        } else {
            return $this->defaultCollectionPattern;
        }
    }
}
