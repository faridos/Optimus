<?php

namespace FrontOffice\OptimusBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use FrontOffice\OptimusBundle\Entity\HistoryClub;

/**
 * HistoryClub controller.
 *
 * @Route("/")
 */
class HistoryClubController extends Controller {

    /**
     * 
     *
     * @Route("profil={id}/historique/club", name="history_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $histories = $em->getRepository('FrontOfficeOptimusBundle:HistoryClub')->findBy(array('user' => $user), array('date' => 'DESC'));
        return $this->render('FrontOfficeUserBundle:Profile:historyClub.html.twig', array('historys_club' => $histories));
    }

    /**
     * 
     *
     * @Route("historyClub/load/{last_id}", name="history_club_ajax", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
    public function getClubLoadAjax($last_id) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $historyclub = new ArrayCollection();
        $historyclub = $em->getRepository('FrontOfficeOptimusBundle:HistoryClub')->historyClubLoadAjax($user->getId(), $last_id);
        if (!$historyclub) {
            throw $this->createNotFoundException('Unable to find history entity.');
        }
        $response = new Response();
        $tabhistoryclub = json_encode($historyclub);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($tabhistoryclub);
        return $response;
    }

}
