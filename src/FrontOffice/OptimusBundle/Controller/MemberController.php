<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Form\MemberType;

/**
 * Member controller.
 *
 * @Route("/")
 */
class MemberController extends Controller
{
    /**
     * Lists all Member entities.
     *
     * @Route("club={id}/members", name="members_club")
     * @Method("GET")
     * @Template("FrontOfficeOptimusBundle:Member:listeAdherents.html.twig")
     */
    public function membersClubAction($id) {
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
         if($club->getActive() == 1)
        {
        $membres = $em->getRepository('FrontOfficeOptimusBundle:Member')->getMembers($id);
        $nbMembers = (count($membres));
        return array(
            'id' => $id,
            'entity' => $club,
            'membresclub' => $membres,
            'nbMembers' => $nbMembers
        );
        }else{
         throw $this->createNotFoundException('Club Introuvable.');
    }
    }

     /**
     * Lists all Member entities.
     *
     * @Route("club={id}/membersrequest", name="members_request")
     * @Method("GET")
     * @Template("FrontOfficeOptimusBundle:Club:demandeClub.html.twig")
     */
    public function membersRequestAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $idc = $user1->getId();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        
          if($club->getActive() == 1)
        {
        $demandes = $em->getRepository('FrontOfficeOptimusBundle:Member')->getMembersRequest($id,$idc);
        return array(
            'id' => $id,
            'club' => $club,
            'demandes' => $demandes
        );
         }else{
         throw $this->createNotFoundException('Club Introuvable.');
    }
    }
    /**
     * Creates a new Member entity.
     *
     * @Route("/", name="member_create")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:Member:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('member_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Member entity.
     *
     * @param Member $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Member entity.
     *
     * @Route("/new", name="member_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Member();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Member entity.
     *
     * @Route("/{id}", name="member_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Member entity.
     *
     * @Route("/{id}/edit", name="member_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
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
    * Creates a form to edit a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Member entity.
     *
     * @Route("/{id}", name="member_update")
     * @Method("PUT")
     * @Template("FrontOfficeOptimusBundle:Member:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('member_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Member entity.
     *
     * @Route("/{id}", name="member_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Member entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('member'));
    }

    /**
     * Creates a form to delete a Member entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('member_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
