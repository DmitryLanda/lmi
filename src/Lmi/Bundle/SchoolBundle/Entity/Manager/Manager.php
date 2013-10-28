<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Entity\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class Manager implements ManagerInterface
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    private $class;

    /**
     * @param EntityManager $entityManager
     * @param string $class
     */
    public function __construct(EntityManager $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $entityManager->getClassMetadata($class)->getName();
        $this->repository = $entityManager->getRepository($this->class);
    }

    /**
     * {@inheritdoc}
     *
     * @return object[]
     */
    public function findAll()
    {
        return $this->findBy(array());
    }

    /**
     * {@inheritdoc}
     *
     * @param array $criteria
     * @param array $orderBy
     * @param integer|null $limit
     * @param integer|null $offset
     * @return object[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $id
     * @return null|object
     */
    public function find($id)
    {
        return $this->find($id);
    }

    /**
     * {@inheritdoc}
     *
     * @param array $criteria
     * @param array $orderBy
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }
}
