<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 
namespace Lmi\Bundle\SchoolBundle\Controller;

use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class BaseController extends Controller
{
    protected function getLogger()
    {
        return $this->get('logger');
    }

    protected function paginate($target, $perPage = 10, array $options = array())
    {
        /** @var Paginator $paginator  */
        $paginator = $this->get('knp_paginator');

        return $paginator->paginate(
            $target,
            $this->getRequest()->query->get('page', 1),
            $perPage,
            $options
        );
    }
}
