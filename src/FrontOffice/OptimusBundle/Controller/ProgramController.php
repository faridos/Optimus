<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Program;
use FrontOffice\OptimusBundle\Entity\Seance;
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Form\ProgramType;
use FrontOffice\OptimusBundle\Form\SeanceType;
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $program = new Program();
        $program->setClubp($entity);
        $form = $this->createForm(new ProgramType(), $program);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $em->persist($program);
            $em->flush();

            $notif = new Notification();
                $notif->setNotificateur($user);
                $notif->setType('addProgram');
                $notif->setClub($program->getClubp());
                $notif->setEntraineur($user);
                $em->persist($notif);
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
     * @Route("club={id_club}/program={id}/show", name="program_show")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Program:show.html.twig")
     */
    public function showProgramAction($id_club, $id) {
        
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id_club);
          if (!$club || $club->getActive() == 0 ) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $programme = $em->getRepository('FrontOfficeOptimusBundle:Program')->find($id);
        $sessions = $em->getRepository('FrontOfficeOptimusBundle:Seance')->findBy(array('program' => $programme));
        // setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        //$date = (strftime("%A"));
     
            $seance = new Seance();
            $seance->setProgram($programme);
            $form = $this->createForm(new SeanceType(), $seance);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($seance);
                $em->flush();
            }
        
        return array(
            'form' => $form->createView(),
            'club' => $club,
            'programme' => $programme,
            'sessions' => $sessions
        );
    }

    /**
     * Creates a new Program entity.
     *
     * @Route("club={id_club}/program={id}/modifier", name="program_edit")
     * @Method("POST")
     * @Template("FrontOfficeOptimusBundle:Program:editProgramClub.html.twig")
     */
    public function editProgramClubAction(Request $request, $id_club, $id) {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id_club);
        $program = $em->getRepository('FrontOfficeOptimusBundle:Program')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!$program) {
            throw $this->createNotFoundException('Unable to find Program entity.');
        }
        $editForm = $this->createForm(new ProgramType, $program);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $notif = new Notification();
                $notif->setNotificateur($user);
                $notif->setType('modifProgram');
                $notif->setClub($club);
                $notif->setEntraineur($user);
                $em->persist($notif);
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $notif = new Notification();
                $notif->setNotificateur($user);
                $notif->setType('suppProgram');
                $notif->setClub($program->getClubp());
                $notif->setEntraineur($user);
                $em->persist($notif);
                $em->flush();
                
        $em->remove($program);
        $em->flush();
        $response = new Response($id);
        return $response;
    }

}
