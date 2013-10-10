<?php

namespace Lmi\Bundle\SchoolBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Lmi\Bundle\SchoolBundle\Form\Map\NewsMap;
use Lmi\Bundle\SchoolBundle\Service\ImageService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Lmi\Bundle\SchoolBundle\Entity\News;
use Lmi\Bundle\SchoolBundle\Form\NewsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * News controller.
 *
 */
class NewsController extends Controller
{

    /**
     * Lists all News entities.
     *
     */
    public function indexAction()
    {
        //todo add pagination
        $newsList = $this->getNewsRepository()->findBy(array(), array('showDate' => 'DESC', 'lastUpdate' => 'DESC'));

        return $this->render('LmiSchoolBundle:News:index.html.twig', array(
            'newsList' => $newsList
        ));
    }
    /**
     * Creates a new News entity.
     */
    public function createAction(Request $request)
    {
        $news  = new News();
        $form = $this->createNewsForm($news);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->updateNewsWithClientData($form, $news);
            $this->saveNews($news);

            return $this->redirect($this->generateUrl('news_show', array('identifier' => $news->getIdentifier())));
        }

        return $this->render('LmiSchoolBundle:News:new.html.twig', array(
            'news' => $news,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new News entity.
     */
    public function newAction()
    {
        $news = new News();
        $form = $this->createNewsForm($news);

        return $this->render('LmiSchoolBundle:News:new.html.twig', array(
            'news' => $news,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a News entity.
     */
    public function showAction($identifier)
    {
        $news = $this->findNewsByIdentifier($identifier);
        if (!$news) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        return $this->render('LmiSchoolBundle:News:show.html.twig', array(
            'news' => $news
        ));
    }

    /**
     * Displays a form to edit an existing News entity.
     */
    public function editAction($identifier)
    {
        $news = $this->findNewsByIdentifier($identifier);
        if (!$news) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createNewsForm($news);

        return $this->render('LmiSchoolBundle:News:edit.html.twig', array(
            'news' => $news,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing News entity.
     */
    public function updateAction(Request $request, $identifier)
    {
        $news = $this->findNewsByIdentifier($identifier);
        if (!$news) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createNewsForm($news);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->updateNewsWithClientData($editForm, $news);
            $this->saveNews($news);

            return $this->redirect($this->generateUrl('news_show', array('identifier' => $news->getIdentifier())));
        }

        return $this->render('LmiSchoolBundle:News:edit.html.twig', array(
            'news' => $news,
            'form' => $editForm->createView()
        ));
    }
    /**
     * Deletes a News entity.
     */
    public function deleteAction($identifier)
    {
        $news = $this->findNewsByIdentifier($identifier);
        if (!$news) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $this->getEntityManager()->remove($news);
        $this->getEntityManager()->flush();

        return new Response('OK');
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    /**
     * @return EntityRepository
     */
    private function getNewsRepository()
    {
        return $this->getEntityManager()->getRepository('LmiSchoolBundle:News');
    }

    /**
     * @param News $news
     * @return Form
     */
    private function createNewsForm(News $news)
    {
        $map = new NewsMap();
        $map->fillFromModel($news);

        return $this->createForm(new NewsType(), $map);
    }

    /**
     * @param Form $form
     * @param News $news
     */
    private function updateNewsWithClientData(Form $form, News $news)
    {
        /** @var NewsMap $newsMap  */
        $newsMap = $form->getData();
        $newsMap->updateModel($news);
        $this->updateAuthor($form, $news);
        $this->updateImage($form, $news);
    }

    /**
     * @param Form $form
     * @param News $news
     */
    private function updateImage(Form $form, News $news)
    {
        if ($form->getData()->getImage()) {
            /** @var ImageService $imageService */
            $imageService = $this->get('lmi_school.service.image');
            /** @var UploadedFile $file */
            $file = $form->getData()->getImage();
            $image = $imageService->save($file, 'news');
            $news->setImage($image);
        }
    }

    /**
     * @param Form $form
     * @param News $news
     */
    private function updateAuthor(Form $form, News $news)
    {
        if (!$form->getData()->getAuthor()) {
            /** @var SecurityContextInterface $securityContext  */
            $securityContext = $this->get('security.context');
            $author = $securityContext->getToken()->getUsername();
            $news->setAuthor($author);
        } else {
            $news->setAuthor($form->getData()->getAuthor());
        }
    }

    /**
     * @param News $news
     */
    private function saveNews(News $news)
    {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $identifier
     * @return News
     */
    private function findNewsByIdentifier($identifier)
    {
        return $this->getNewsRepository()->findOneByIdentifier($identifier);
    }
}
