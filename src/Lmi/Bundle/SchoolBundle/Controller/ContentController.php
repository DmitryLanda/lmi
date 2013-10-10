<?php
/**
* @author Dmitry Landa <dmitry.landa@yandex.ru>
*
* For the full copyright and license information, please view the
* LICENSE file that was distributed with this source code.
*/ 

namespace Lmi\Bundle\SchoolBundle\Controller;

use Lmi\Bundle\SchoolBundle\Entity\SitePage;
use Lmi\Bundle\SchoolBundle\Form\SitePageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Dmitry Landa <dmitry.landa@yandex.ru>
 */
class ContentController extends Controller
{
    /**
     * @Template()
     *
     * @param string $slug
     * @return array
     * @throws NotFoundHttpException
     */
    public function getPageAction($slug)
    {
        $page = $this->getDoctrine()
            ->getRepository('LmiSchoolBundle:SitePage')
            ->findOneBy(array('slug' => $slug));
        if (!$page) {
            throw $this->createNotFoundException('Нет такого раздела');
        }

        return array(
            'page' => $page
        );
    }

    /**
     * @Template("LmiSchoolBundle:Content:editPage.html.twig")
     *
     * @param Request $request
     * @return Response
     */
    public function newPageAction(Request $request)
    {
        $form = $this->createForm(new SitePageType(), new SitePage());

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Template("LmiSchoolBundle:Content:editPage.html.twig")
     *
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function createPageAction(Request $request)
    {
        $page = new SitePage();
        $form = $this->createForm(new SitePageType(), $page);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $page->getSlug())));
        }

        return array(
            'form' => $form->createView(),
            'page' => $page
        );

    }

    /**
     * @Template("LmiSchoolBundle:Content:editPage.html.twig")
     *
     * @param string $slug
     * @return Response
     * @throws NotFoundHttpException
     */
    public function editPageAction($slug)
    {
        $page = $this->getDoctrine()
            ->getRepository('LmiSchoolBundle:SitePage')
            ->findOneBy(array('slug' => $slug));

        if (!$page) {
            throw $this->createNotFoundException('Нет такого раздела');
        }

        $form = $this->createForm(new SitePageType(), $page);

        return array(
            'form' => $form->createView(),
            'page' => $page
        );
    }

    /**
     * @Template("LmiSchoolBundle:Content:editPage.html.twig")
     *
     * @param Request $request
     * @param string $slug
     * @return Response|RedirectResponse
     * @throws NotFoundHttpException
     */
    public function updatePageAction(Request $request, $slug)
    {
        $page = $this->getDoctrine()
            ->getRepository('LmiSchoolBundle:SitePage')
            ->findOneBy(array('slug' => $slug));

        if (!$page) {
            throw $this->createNotFoundException('Нет такого раздела');
        }

        $form = $this->createForm(new SitePageType(), $page);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('page_show', array('slug' => $page->getSlug())));
        }

        return array(
            'form' => $form->createView(),
            'page' => $page
        );
    }

    public function deletePageAction($slug)
    {
        $page = $this->getDoctrine()
            ->getRepository('LmiSchoolBundle:SitePage')
            ->findOneBy(array('slug' => $slug));

        if (!$page) {
            throw $this->createNotFoundException('Нет такого раздела');
        }

        $this->getDoctrine()->getManager()->remove($page);
        $this->getDoctrine()->getManager()->flush();

        return new Response('OK');
    }
}
