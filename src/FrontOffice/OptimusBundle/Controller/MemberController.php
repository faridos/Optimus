<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Form\MemberType;
use DateTime;

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
     * 
     *
     * @Route("demande={id}/accept", name="accept_demande", options={"expose"=true})
     * @Method("GET|POST")
     */
    public function confirmAction($id) {
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);
        if (!$demande) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        $demande->setConfirmed(true);
        if ($demande->getConfirmed() == true) {
            $date = new DateTime();
            $demande->setDateconfirm($date);
            $em->persist($demande);
            $em->flush();
            $response = new Response($id);
            return $response;
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
        if (!empty($demande)) {
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
        $demande = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);
        if (!empty($demande)) {
            $em->remove($demande);
            $em->flush();
            $response = new Response($id);
            return $response;
        }
    }

}
