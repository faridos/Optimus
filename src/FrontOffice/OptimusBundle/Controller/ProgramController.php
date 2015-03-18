<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Program;
use FrontOffice\OptimusBundle\Form\ProgramType;

/**
 * Program controller.
 *
 * @Route("/")
 */
class ProgramController extends Controller
{

    
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
}
