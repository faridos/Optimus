<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Competition;
use FrontOffice\OptimusBundle\Form\CompetitionType;
use FrontOffice\OptimusBundle\Form\UpdateCompetitionType;
use \DateTime;


/**
 * Competition controller.
 *
 * @Route("/competition")
 */
class CompetitionController extends Controller
{
    
    /**
     * 
     *
     * @Route("/{id}/ajouter", name="add-competition")
     * @Method("GET|POST")
     * @Template()
     */
    public function addAction(Request $request,$id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $competition = new Competition();
        $competition->setCreateur($user);
        $competition->setClub($club);
        $competition->setDateModification(null);
        $competition->setNbrvu(0);
        $form = $this->createForm(new CompetitionType, $competition);
        $req = $this->get('request');
        if ($req->getMethod() == "POST") {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($competition);
                $em->flush();
      $request->getSession()->getFlashBag()->add('AjoutCompetition', "Compétition  a été creé avec success.");
                return $this->redirect($this->generateUrl('show_club', array('id' => $club->getId())));
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Competition:new.html.twig', array('form' => $form->createView(), 'club' => $club));
    }
      /**
     * 
     *
     * @Route("/{id}/modifier", name="update-competition")
     * @Method("GET|POST")
     * @Template()
     */
    public function updatecAction(Request $request, $id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        if (!$competition ) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $editForm = $this->createForm(new UpdateCompetitionType(), $competition);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $competition->setDateModification(new DateTime());
            $em->flush();
            
      
            return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Competition:edit.html.twig', array(
                    'competition' => $competition,
                    
                    'edit_form' => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Reward entity.
     *
     * @Route("c/{id}/supprimer", name="delete_competition", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteCompetitionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        if (!$competition) {
            throw $this->createNotFoundException('Unable to find competition entity.');
        }
        $em->remove($competition);
        $em->flush();
        $response = new Response($id);
        return $response;
    }



    /**
     * Lists all Competition entities.
     *
     * @Route("/", name="competition")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontOfficeOptimusBundle:Competition')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Competition entity.
     *
     * @Route("/", name="competition_create")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:Competition:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Competition();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('competition_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Competition entity.
     *
     * @param Competition $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Competition $entity)
    {
        $form = $this->createForm(new CompetitionType(), $entity, array(
            'action' => $this->generateUrl('competition_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Competition entity.
     *
     * @Route("/new", name="competition_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Competition();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{id}", name="competition_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Competition entity.
     *
     * @Route("/{id}/edit", name="competition_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
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
    * Creates a form to edit a Competition entity.
    *
    * @param Competition $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Competition $entity)
    {
        $form = $this->createForm(new CompetitionType(), $entity, array(
            'action' => $this->generateUrl('competition_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Competition entity.
     *
     * @Route("/{id}", name="competition_update")
     * @Method("PUT")
     * @Template("FrontOfficeOptimusBundle:Competition:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('competition_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Competition entity.
     *
     * @Route("/{id}", name="competition_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Competition entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('competition'));
    }

    /**
     * Creates a form to delete a Competition entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('competition_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
