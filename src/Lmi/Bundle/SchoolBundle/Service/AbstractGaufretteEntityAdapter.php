<?php
/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Lmi\Bundle\SchoolBundle\Service;

use Gaufrette\Adapter as GaufretteAdapter;
use Lmi\Bundle\SchoolBundle\Model\Manager\EntityManager;
use Psr\Log\LoggerInterface;

/**
 * @author Dmitry Landa <dmitry.landa@opensoftdev.ru>
 */
abstract class AbstractGaufretteEntityAdapter implements GaufretteAdapter
{
    protected $entityManager;
    protected $logger;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Reads the content of the file
     *
     * @param string $key
     *
     * @return string|boolean if cannot read content
     */
    public function read($key)
    {
        $entity = $this->entityManager->getOneById($key);

        return $entity;
    }

    /**
     * Writes the given content into the file
     *
     * @param string $key
     * @param string $content
     *
     * @return integer|boolean The number of bytes that were written into the file
     */
    public function write($key, $content)
    {
        return $this->entityManager->create($key, $content);
    }

    /**
     * Indicates whether the file exists
     *
     * @param string $key
     *
     * @return boolean
     */
    public function exists($key)
    {
        return true;
    }

    /**
     * Returns an array of all keys (files and directories)
     *
     * @return array
     */
    public function keys()
    {
        $albums =  $this->entityManager->getAll();
        $keys = array();

        foreach ($albums as $album) {
            $keys[] = $album->getId();
        }

        return $keys;
    }

    /**
     * Returns the last modified time
     *
     * @param string $key
     *
     * @return integer|boolean An UNIX like timestamp or false
     */
    public function mtime($key)
    {
        $entity = $this->entityManager->getOneById($key);
        if (!$entity) {
            return false;
        }

        return $entity->getEdited()->getTimestamp();
    }

    /**
     * Deletes the file
     *
     * @param string $key
     *
     * @return boolean
     */
    public function delete($key)
    {
        return $this->entityManager->remove($key);
    }

    /**
     * Renames a file
     *
     * @param string $sourceKey
     * @param string $targetKey
     *
     * @return boolean
     */
    public function rename($sourceKey, $targetKey)
    {
        // TODO: Implement rename() method.
    }

    /**
     * Check if key is directory
     *
     * @param string $key
     *
     * @return boolean
     */
    public function isDirectory($key)
    {
        return $this->isAlbum();
    }

    /**
     * @return boolean
     */
    abstract protected function isAlbum();
}
