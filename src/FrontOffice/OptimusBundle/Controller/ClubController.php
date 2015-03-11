<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Club;
use FrontOffice\OptimusBundle\Form\ClubType;
use FrontOffice\OptimusBundle\Form\UpdateClubType;
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
        $club->setLienWeb('http://www.sport.com');
        $form = $this->createForm(new ClubType(), $club);
        $req = $this->get('request');
        if ($req->getMethod() == 'POST') {
            $form->bind($req);

            $em->persist($club);
            $em->flush();

            return $this->redirect($this->generateUrl('show_club', array('id' => $club->getId())));
//                $action = 'add';
//                $clubevent = new HistoryClubEvent($user, $club, $action);
//                $clubnotification = new NotificationClubEvent($user, $club, $action);
//                $dispatcher = $this->get('event_dispatcher');
//                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
//                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_CLUB, $clubnotification);
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
        if (!$club || $club->getActive() == 0) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
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
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$club || $club->getActive() == 0) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        if ($club->getCreateur() == $user) {
            $editForm = $this->createForm(new UpdateClubType(), $club);
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();
                // add History 
                $action = 'update';
                $clubevent = new HistoryClubEvent($user, $club, $action);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
                return $this->redirect($this->generateUrl('show_club', array('id' => $id)));
            }
            return array(
                'club' => $club,
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
        if (!$entity || $entity->getActive() == 0) {
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

            return $this->redirect($this->generateUrl('  clubs_member', array('id' => $user->getId())));
        }
    }

    /**
     * 
     *
     * @Route("club={id}/palmares", name="palmares_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPalmaresUserAction($id) {
        $em = $this->getDoctrine()->getManager();

        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $rewards = $em->getRepository('FrontOfficeOptimusBundle:Reward')->findBy(array('club' => $club));
        return $this->render('FrontOfficeOptimusBundle:Club:palmaresClub.html.twig', array('rewards' => $rewards, 'club' => $club));
    }

    /**
     * 
     *
     * @Route("club={id}/albums", name="albums_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getAlbumsClubAction($id) {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $albums = $em->getRepository('FrontOfficeOptimusBundle:Album')->findBy(array('club' => $club));
        return $this->render('FrontOfficeOptimusBundle:Club:albumsClub.html.twig', array('albums' => $albums, 'club' => $club));
    }

    /**
     * 
     *
     * @Route("club={id}/album={id_album}/photos", name="photos_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPhotosUserAction($id_album) {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($id_album);
        $club = $album->getClub();
        $photos = $em->getRepository('FrontOfficeOptimusBundle:Photo')->findby(array('album' => $album));
        return $this->render('FrontOfficeOptimusBundle:Club:photosClub.html.twig', array('album' => $album, 'club' => $club, 'photos' => $photos));
    }

    /**
     * Creates a new Club entity.
     *
     * @Route("/club={id}/request", name="request_club")
     * @Method("POST|GET")
     */
    public function requestAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $adherent = $em->getRepository('FrontOfficeOptimusBundle:Member')->findBy(array('member' => $user, 'clubad' => $club));
        if (empty($adherent)) {
            $member = new Member();
            $member->setClubad($club);
            $member->setMember($this->container->get('security.context')->getToken()->getUser());
            // Étape 1 : On « persiste » l'entité
            $em->persist($member);
            // Étape 2 : On « flush » tout ce qui a été persisté avant
            $em->flush();
            // Reste de la méthode qu'on avait déjà écrit
        } else {
            $this->get('session')->getFlashBag('notice', 'ok');
        }
        return $this->redirect($this->generateUrl('show_club', array('id' => $id)));
    }

}
