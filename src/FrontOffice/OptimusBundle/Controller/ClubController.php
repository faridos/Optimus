<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Club;
use FrontOffice\OptimusBundle\Form\ClubType;
use FrontOffice\OptimusBundle\Form\ClubPhotoType;
use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Entity\Comment;
use FrontOffice\OptimusBundle\Entity\Palmares;


use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FrontOffice\OptimusBundle\Form\MemberType;
use FrontOffice\OptimusBundle\Controller\MemberController;
use \DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use FrontOffice\OptimusBundle\Event\HistoryClubEvent;
use FrontOffice\OptimusBundle\Event\NotificationClubEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;


/**
 * Club controller.
 *
 * @Route("/")
 */
class ClubController extends Controller {
    
    /**
     * 
     *
     * @Route("club/ajouter", name="add_club")

     * @Template("FrontOfficeOptimusBundle:Club:add.html.twig")
     */
    public function addClubAction() {
        if (!$this->get('security.context')->isGranted('ROLE_ENTRAINEUR')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('Accès limité aux Entraîneurs.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $club = new Club();
        $club->setCreateur($user);
        $club->setActive(1);
        $form = $this->createForm(new ClubType(), $club);
        $req = $this->get('request');
        if ($req->getMethod() == 'POST') {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($club);
                $em->flush();
                  var_dump($club);die();
//                $action = 'add';
//                $clubevent = new HistoryClubEvent($user, $club, $action);
//                $clubnotification = new NotificationClubEvent($user, $club, $action);
//                $dispatcher = $this->get('event_dispatcher');
//                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
//                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_CLUB, $clubnotification);
            }
        }
        return array(
            'form' => $form->createView(),
            'user' => $user);
    }
    /**
     * 
     *
     * @Route("club={id}", name="show_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showClubAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user1 = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
       
        return $this->render('FrontOfficeOptimusBundle:Club:show_club.html.twig', array('club' => $club, 'user1' => $user1));
    }

    /**
     * Edits an existing Club entity.
     *
     * @Route("club={id}/modifier", name="club_update")

     * @Template("FrontOfficeOptimusBundle:Club:edit.html.twig")
     */
    public function editClubAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$entity || $entity->getActive()==0) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        if ($entity->getCreateur() == $user) {
            $editForm = $this->createForm(new ClubType, $entity);
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();
                // add History 
                $action = 'update';
                $clubevent = new HistoryClubEvent($user, $entity, $action);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
            }
            return array(
                'entity' => $entity,
                'form' => $editForm->createView(),
                'user' => $user,
            );
        }
    }

    /**
     * Deletes a Club entity.
     *
     * @Route("/club={id}/supprimer", name="club_delete")
     * 
     */
    public function deleteAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$entity || $entity->getActive()==0) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        if ($entity->getCreateur() == $user) {
            $entity->setActive(false);
            $em->persist($entity);
            $em->flush();
            // add History 
            $action = 'delete';
                $clubevent = new HistoryClubEvent($user, $entity, $action);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
            
        }
    }

}
