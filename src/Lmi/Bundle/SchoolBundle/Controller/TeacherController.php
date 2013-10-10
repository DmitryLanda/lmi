<?php

namespace Lmi\Bundle\SchoolBundle\Controller;

use Lmi\Bundle\SchoolBundle\Form\Map\TeacherMap;
use Lmi\Bundle\SchoolBundle\Service\ImageService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Lmi\Bundle\SchoolBundle\Entity\Teacher;
use Lmi\Bundle\SchoolBundle\Form\TeacherType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Teacher controller.
 *
 */
class TeacherController extends Controller
{

    /**
     * Lists all Teacher entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LmiSchoolBundle:Teacher')->findAll();

        return $this->render('LmiSchoolBundle:Teacher:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Teacher entity.
     *
     */
    public function createAction(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createTeacherForm($teacher);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->updateTeacherWithClientData($form, $teacher);
            $this->saveTeacher($teacher);

            return $this->redirect($this->generateUrl('teacher_show', array('name' => $teacher->getCanonicalName())));
        }

        return $this->render('LmiSchoolBundle:Teacher:new.html.twig', array(
            'teacher' => $teacher,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Teacher entity.
     *
     */
    public function newAction()
    {
        $teacher = new Teacher();
        $form   = $this->createTeacherForm($teacher);

        return $this->render('LmiSchoolBundle:Teacher:new.html.twig', array(
            'teacher' => $teacher,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Teacher entity.
     *
     */
    public function showAction($name)
    {
        $teacher = $this->findTeacherByName($name);

        if (!$teacher) {
            throw $this->createNotFoundException('Unable to find Teacher entity.');
        }

        return $this->render('LmiSchoolBundle:Teacher:show.html.twig', array(
            'teacher' => $teacher
        ));
    }

    /**
     * Displays a form to edit an existing Teacher entity.
     *
     */
    public function editAction($name)
    {
        $teacher = $this->findTeacherByName($name);

        if (!$teacher) {
            throw $this->createNotFoundException('Unable to find Teacher entity.');
        }

        $editForm = $this->createTeacherForm($teacher);

        return $this->render('LmiSchoolBundle:Teacher:edit.html.twig', array(
            'teacher'      => $teacher,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Edits an existing Teacher entity.
     *
     */
    public function updateAction(Request $request, $name)
    {
        $teacher = $this->findTeacherByName($name);

        if (!$teacher) {
            throw $this->createNotFoundException('Unable to find Teacher entity.');
        }

        $editForm = $this->createTeacherForm($teacher);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->updateTeacherWithClientData($editForm, $teacher);
            $this->saveTeacher($teacher);

            return $this->redirect($this->generateUrl('teacher_edit', array('name' => $name)));
        }

        return $this->render('LmiSchoolBundle:Teacher:edit.html.twig', array(
            'teacher'      => $teacher,
            'edit_form'   => $editForm->createView()
        ));
    }
    /**
     * Deletes a Teacher entity.
     *
     */
    public function deleteAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $teacher = $this->findTeacherByName($name);

        if (!$teacher) {
            throw $this->createNotFoundException('Unable to find Teacher entity.');
        }

        $em->remove($teacher);
        $em->flush();

        return new Response('OK');
    }

    /**
     * @param Form $form
     * @param Teacher $teacher
     */
    private function updatePhoto(Form $form, Teacher $teacher)
    {
        if ($form->getData()->getPhoto()) {
            /** @var ImageService $imageService */
            $imageService = $this->get('lmi_school.service.image');
            /** @var UploadedFile $file */
            $file = $form->getData()->getPhoto();
            $image = $imageService->save($file, 'teachers');
            $teacher->setPhoto($image);
        }
    }

    /**
     * @param Teacher $teacher
     */
    private function saveTeacher(Teacher $teacher)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($teacher);
        $em->flush();
    }

    /**
     * @param $teacher
     * @return Form
     */
    public function createTeacherForm($teacher)
    {
        $type = new TeacherType();
        $map = new TeacherMap();
        $map->fillFromModel($teacher);

        return $this->createForm($type, $map);
    }

    /**
     * @param Form $form
     * @param Teacher $teacher
     */
    private function updateTeacherWithClientData(Form $form, Teacher $teacher)
    {
        /** @var TeacherMap $teacherMap  */
        $teacherMap = $form->getData();
        $this->clearContacts($teacher);
        $this->clearProjects($teacher);
        $this->clearRegards($teacher);
        $teacherMap->updateModel($teacher);
        $this->updatePhoto($form, $teacher);
    }

    /**
     * @param $name
     * @return mixed
     */
    private function findTeacherByName($name)
    {
        $em = $this->getDoctrine()->getManager();

        $teacher = $em->getRepository('LmiSchoolBundle:Teacher')->findOneByCanonicalName($name);

        return $teacher;
    }

    /**
     * @param Teacher $teacher
     */
    private function clearContacts(Teacher $teacher)
    {
        foreach ($teacher->getContacts() as $contact) {
            $teacher->removeContact($contact);
            $this->getDoctrine()->getManager()->remove($contact);
        }
    }

    /**
     * @param Teacher $teacher
     */
    private function clearProjects(Teacher $teacher)
    {
        foreach ($teacher->getProjects() as $project) {
            $teacher->removeProject($project);
            $this->getDoctrine()->getManager()->remove($project);
        }
    }

    /**
     * @param Teacher $teacher
     */
    private function clearRegards(Teacher $teacher)
    {
        foreach ($teacher->getRegards() as $regard) {
            $teacher->removeRegard($regard);
            $this->getDoctrine()->getManager()->remove($regard);
        }
    }

}
