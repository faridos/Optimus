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
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\HistoryClub;
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
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;

/**
 * Club controller.
 *
 * @Route("/")
 */
class ClubController extends Controller {

    public function ajouterClubAction() {
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
                $em = $this->getDoctrine()->getManager();
                $em->persist($club);
                $em->flush();
                //add notification lors de creation d'un nouveau club
                //add member club lors de creation d'un nouveau club
                // add History 
                $clubevent = new HistoryClubEvent($user, $club);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Club:add.html.twig', array(
                    'form' => $form->createView(),
                    'user' => $user));
    }

}
