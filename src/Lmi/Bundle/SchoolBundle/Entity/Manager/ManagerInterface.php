<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/
namespace Lmi\Bundle\SchoolBundle\Entity\Manager;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
interface ManagerInterface
{
    /**
     * @return object[]
     */
    public function findAll();

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param integer|null $limit
     * @param integer|null $offset
     * @return object[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param mixed $id
     * @return object|null
     */
    public function find($id);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return object|null
     */
    public function findOneBy(array $criteria, array $orderBy = null);
}
