<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Seance;
use FrontOffice\OptimusBundle\Form\SeanceType;
use \DateTime;
/**
 * Seance controller.
 *
 * @Route("/seance")
 */
class SeanceController extends Controller
{

    /**
     * Lists all Seance entities.
     *
     * @Route("/", name="seance")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontOfficeOptimusBundle:Seance')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Seance entity.
     *
     * @Route("/", name="seance_create")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:Seance:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Seance();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('seance_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Seance entity.
     *
     * @param Seance $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Seance $entity)
    {
        $form = $this->createForm(new SeanceType(), $entity, array(
            'action' => $this->generateUrl('seance_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Seance entity.
     *
     * @Route("/new", name="seance_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Seance();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Seance entity.
     *
     * @Route("/{id}", name="seance_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Seance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seance entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Seance entity.
     *
     * @Route("/{id}/edit", name="seance_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Seance')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Seance entity.');
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
    * Creates a form to edit a Seance entity.
    *
    * @param Seance $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Seance $entity)
    {
        $form = $this->createForm(new SeanceType(), $entity, array(
            'action' => $this->generateUrl('seance_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Seance entity.
     *
     * @Route("/{id}/seance", name="seance_update", options={"expose"=true})
     * @Method("GET|PUT")
     * @Template("FrontOfficeOptimusBundle:Seance:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $seance = $em->getRepository('FrontOfficeOptimusBundle:Seance')->find($id);
        
        $request = $this->get('request');
      $nom = $request->get("nom");
      $decr = $request->get("desc");
      $debut = $request->get("debut");
      $fin = $request->get("fin");
        $seance->setNom($nom);
                $seance->setDescription($decr);
                $seance->setDatedebut($debut);
                $seance->setDatefin($fin);
//       
        $em->merge($seance);
        $em->flush();

        return $response = new Response($id);

       
    }
    /**
     * Deletes a Seance entity.
     *
     * @Route("/seance/{id}/delete", name="seance_delete", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontOfficeOptimusBundle:Seance')->find($id);
            $em->remove($entity);
            $em->flush();
        

       return $response = new Response($id);
    }

    /**
     * Creates a form to delete a Seance entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('seance_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    /**
     * Creates a new Seance entity.
     *
     * @Route("club={id}/program={idp}/ajouter_seance", name="seance_create", options={"expose"=true})
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Seance:new.html.twig")
     */
    public function createSeanceAction(Request $request,$id,$idp)
    {
        $em = $this->getDoctrine()->getManager();
        $entity   = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $program = $em->getRepository('FrontOfficeOptimusBundle:Program')->find($idp);
      $request = $this->get('request');
      $nom = $request->get("nom");
      $decr = $request->get("desc");
      $debut = $request->get("debut");
      $fin = $request->get("fin");
     
        $seance = new Seance();
        $seance->setProgram($program);
        $seance->setNom($nom);
                $seance->setDescription($decr);
                $seance->setDatedebut($debut);
                $seance->setDatefin($fin);
//       
        $em->persist($seance);
        $em->flush();

        return $response = new Response($seance->getId());
        //}

       //
    }

}
