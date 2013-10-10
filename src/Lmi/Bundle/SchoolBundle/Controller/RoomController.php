<?php

namespace Lmi\Bundle\SchoolBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Lmi\Bundle\SchoolBundle\Entity\Room;
use Lmi\Bundle\SchoolBundle\Form\RoomType;

/**
 * Room controller.
 *
 */
class RoomController extends Controller
{

    /**
     * Lists all Room entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LmiSchoolBundle:Room')->findAll();

        return $this->render('LmiSchoolBundle:Room:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Room entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Room();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('room_show', array('number' => $entity->getNumber())));
        }

        return $this->render('LmiSchoolBundle:Room:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Room entity.
    *
    * @param Room $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Room $entity)
    {
        $form = $this->createForm(new RoomType(), $entity, array(
            'action' => $this->generateUrl('room_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Room entity.
     *
     */
    public function newAction()
    {
        $entity = new Room();
        $form   = $this->createCreateForm($entity);

        return $this->render('LmiSchoolBundle:Room:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Room entity.
     *
     */
    public function showAction($number)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LmiSchoolBundle:Room')->findOneByNumber($number);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Room entity.');
        }

        $deleteForm = $this->createDeleteForm($number);

        return $this->render('LmiSchoolBundle:Room:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Room entity.
     *
     */
    public function editAction($number)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LmiSchoolBundle:Room')->findOneByNumber($number);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Room entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($number);

        return $this->render('LmiSchoolBundle:Room:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Room entity.
    *
    * @param Room $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Room $entity)
    {
        $form = $this->createForm(new RoomType(), $entity, array(
            'action' => $this->generateUrl('room_update', array('number' => $entity->getNumber())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Room entity.
     *
     */
    public function updateAction(Request $request, $number)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LmiSchoolBundle:Room')->findOneByNumber($number);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Room entity.');
        }

        $deleteForm = $this->createDeleteForm($number);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('room_edit', array('number' => $number)));
        }

        return $this->render('LmiSchoolBundle:Room:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Room entity.
     *
     */
    public function deleteAction(Request $request, $number)
    {
        $form = $this->createDeleteForm($number);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LmiSchoolBundle:Room')->findOneByNumber($number);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Room entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('room'));
    }

    /**
     * Creates a form to delete a Room entity by id.
     *
     * @param mixed $number The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($number)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('room_delete', array('number' => $number)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
