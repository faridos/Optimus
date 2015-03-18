<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Program;
use FrontOffice\OptimusBundle\Form\ProgramType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Program controller.
 *
 * @Route("/")
 */
class ProgramController extends Controller {

    /**
     * Creates a new Program entity.
     *
     * @Route("club={id}/program/new", name="program_create")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:Club:newProgram.html.twig")
     */
    public function createAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $program = new Program();
        $program->setClubp($entity);
        $form = $this->createForm(new ProgramType(), $program);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em->persist($program);
            $em->flush();

            return $this->redirect($this->generateUrl('show_club', array('id' => $entity->getId())));
        }
        return array(
            'program' => $program,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Program entity.
     *
     * @Route("club={id_club}/program={id}/modifier", name="program_edit")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:Program:editProgramClub.html.twig")
     */
    public function editProgramClubAction(Request $request,$id_club, $id) {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id_club);
        $program = $em->getRepository('FrontOfficeOptimusBundle:Program')->find($id);

        if (!$program) {
            throw $this->createNotFoundException('Unable to find Program entity.');
        }
        $editForm = $this->createForm(new ProgramType, $program);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('show_club', array('id' => $id_club)));
        }

        return array(
            'club' => $club,
            'program' => $program,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Album entity.
     *
     * @Route("program={id}/supprimer", name="program_delete", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction($id) {
       
        $em = $this->getDoctrine()->getManager();
        $program = $em->getRepository('FrontOfficeOptimusBundle:Program')->find($id);
        $em->remove($program);
        $em->flush();
        $response = new Response($id);
         return $response;
    }

}
