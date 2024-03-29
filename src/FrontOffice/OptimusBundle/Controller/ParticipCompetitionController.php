<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\ParticipCompetition;
use FrontOffice\OptimusBundle\Form\ParticipCompetitionType;

/**
 * ParticipCompetition controller.
 *
 * @Route("/participcompetition")
 */
class ParticipCompetitionController extends Controller
{

    /**
     * Lists all ParticipCompetition entities.
     *
     * @Route("/", name="participcompetition")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ParticipCompetition entity.
     *
     * @Route("/", name="participcompetition_create")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:ParticipCompetition:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ParticipCompetition();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('participcompetition_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ParticipCompetition entity.
     *
     * @param ParticipCompetition $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ParticipCompetition $entity)
    {
        $form = $this->createForm(new ParticipCompetitionType(), $entity, array(
            'action' => $this->generateUrl('participcompetition_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ParticipCompetition entity.
     *
     * @Route("/new", name="participcompetition_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ParticipCompetition();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ParticipCompetition entity.
     *
     * @Route("/{id}", name="participcompetition_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ParticipCompetition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ParticipCompetition entity.
     *
     * @Route("/{id}/edit", name="participcompetition_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ParticipCompetition entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a ParticipCompetition entity.
    *
    * @param ParticipCompetition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ParticipCompetition $entity)
    {
        $form = $this->createForm(new ParticipCompetitionType(), $entity, array(
            'action' => $this->generateUrl('participcompetition_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ParticipCompetition entity.
     *
     * @Route("/{id}", name="participcompetition_update")
     * @Method("PUT")
     * @Template("FrontOfficeOptimusBundle:ParticipCompetition:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ParticipCompetition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('participcompetition_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ParticipCompetition entity.
     *
     * @Route("/{id}", name="participcompetition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ParticipCompetition entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('participcompetition'));
    }

    /**
     * Creates a form to delete a ParticipCompetition entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participcompetition_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    
}
