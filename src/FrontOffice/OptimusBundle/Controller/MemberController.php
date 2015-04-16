<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Entity\CompteClub;
use FrontOffice\OptimusBundle\Form\MemberType;
use \DateTime;

/**
 * Member controller.
 *
 * @Route("/")
 */
class MemberController extends Controller {

    /**
     * Lists all Member entities.
     *
     * @Route("club={id}/members", name="members_club")
     * @Method("GET")
     * @Template("FrontOfficeOptimusBundle:Club:listeAdherents.html.twig")
     */
    public function membersClubAction($id) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if ($club->getActive() == 1) {
            $membres = $em->getRepository('FrontOfficeOptimusBundle:Member')->getMembers($id,$club->getCreateur()->getId());
            $nbMembers = (count($membres));
            return array(
                'id' => $id,
                'club' => $club,
                'membresclub' => $membres,
                'nbMembers' => $nbMembers
            );
        } else {
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

        if ($club->getActive() == 1) {
            $demandes = $em->getRepository('FrontOfficeOptimusBundle:Member')->getMembersRequest($id, $idc);
            return array(
                'id' => $id,
                'club' => $club,
                'demandes' => $demandes
            );
        } else {
            throw $this->createNotFoundException('Club Introuvable.');
        }
    }

  

    /**
     * Creates a new Member entity.
     *
     * @Route("demande={id}/delete", name="delete_demande", options={"expose"=true})
     * 
     * 
     */
    public function deleteDemandeAction($id) {
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!empty($demande)) {
            
            $notif = new Notification();
            $notif->setNotificateur($user);
            $notif->setType('RefDemClub');
            $notif->setClub($demande->getClubad());
            $notif->setEntraineur($demande->getMember());
            $em->persist($notif);
            $em->flush();
            
            $em->remove($demande);
            $em->flush();
            
            $response = new Response($id);
            return $response;
        }
    }

     /**
     * Creates a new Member entity.
     *
     * @Route("member={id}/delete", name="delete_member", options={"expose"=true})
     * 
     * 
     */
    public function deleteMemberAction($id) {
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);
        if (!empty($member)) {
            $member->setConfirmed(2);
            $em->merge($member);
            $em->flush();
            
            $compte = new CompteClub();
            $compte->setMember($member);
            $compte->setDateExit(new DateTime());
            $compte->setType('desactivé');
             $compte->setClub($member->getClubad());
            $em->persist($compte);
            $em->flush();
            
            $user = $this->container->get('security.context')->getToken()->getUser();
            $notif = new Notification();
            $notif->setNotificateur($user);
            $notif->setType('DeleteMemClub');
            $notif->setClub($member->getClubad());
            $notif->setEntraineur($member->getMember());
            $em->persist($notif);
            $em->flush();
            
            $response = new Response($id);
            return $response;
            
        }
    }

}
