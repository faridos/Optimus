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
use FrontOffice\OptimusBundle\Form\CompetitionPhotoType;
use FrontOffice\OptimusBundle\Event\ParticipationCompetitionEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
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
        $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->findOneBy(array('clubad' => $club,'member' =>$user));
        $competition = new Competition();
        $competition->setCreateur($user);
        $competition->setClub($club);
        $competition->setActive(true); 
        $competition->setDateModification(null);
        $competition->setNbrvu(0);
        $form = $this->createForm(new CompetitionType, $competition);
        $req = $this->get('request');
        if ($req->getMethod() == "POST") {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($competition);
                $em->flush();
                 $eventparticipation = new ParticipationCompetitionEvent($member, $competition);
                 $dispatcher = $this->get('event_dispatcher');
                 $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_COMPETITION_REGISTER, $eventparticipation);
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
        $request->getSession()->getFlashBag()->add('UpdateCompetition', "Compétition  a été modifier.");
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
        $competition->setActive(false);
        $em->merge($competition);
        $em->flush();
        $response = new Response($id);
        return $response;
    }
    /**
     * Deletes a Reward entity.
     *
     * @Route("/{id}/delete", name="delete_competition_profil", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request,$id) {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $club = $competition->getClub();
        if (!$competition) {
            throw $this->createNotFoundException('Unable to find competition entity.');
        }
        $competition->setActive(false);
        $em->merge($competition);
        $em->flush();
        $request->getSession()->getFlashBag()->add('SupprissionCompetition', "Compétition  a été supprimer.");
        return $this->redirect($this->generateUrl('show_club', array('id' => $club->getId())));
    }
    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{id}", name="competition_show")
     * @Method("GET")
     * @Template("FrontOfficeOptimusBundle:Competition:showCempetition.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
         $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
       
        if (!$competition) {
            throw $this->createNotFoundException('Unable to find Competition entity.');
        }
           $club = $competition->getClub();
           $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->findOneBy(array('clubad' => $club,'member' =>$user));
         $nbr1 = $competition->getNbrvu();
        $nbr = $nbr1 + 1 ;
        $competition->setNbrvu($nbr);
        $em->merge($competition);
        $em->flush();
        return array(
            'competition'      => $competition,
            'member' => $member
        );
    }
     /**
     * 
     *
     * @Route("/{id}/editphoto", name="setting_competition_photo", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editPhotoCompetitionAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
       $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        
        
            $editForm = $this->createForm(new CompetitionPhotoType(), $competition);
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
            }
            return $this->render('FrontOfficeOptimusBundle:Competition:editPhoto.html.twig', array('competition' => $competition, 'form' => $editForm->createView()));
        
    }

  
}
