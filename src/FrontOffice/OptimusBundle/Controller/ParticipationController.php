<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Participation controller.
 *
 * @Route("/")
 */
class ParticipationController extends Controller {

    /**
     * 
     *
     * @Route("event={id}/participer", name="add_participation")
     * 
     * @Method("GET|POST")
     */
    public function addParticipationAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $participation = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findOneBy(array('event' => $event, 'participant' => $user));
        if ($participation != null)
            return new Response("Echec : déja participé !!");

        $newparticipation = new Participation();
        $newparticipation->setParticipant($user);
        $newparticipation->setEvent($event);


        $em->persist($newparticipation);
        $em->flush();
        return die('ok');
    }

    public function deleteAction($event_id, $route) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($event_id);
        $p = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findBy(array('event' => $event, 'participant' => $user));
        if (!$p) {
            throw $this->createNotFoundException('Erreur : Participation invalide !');
        }

        $em->remove($p[0]);
        $em->flush();

        if ($route == 'accueil')
            return $this->redirect($this->generateUrl('user_index'));
        elseif ($route == 'event')
            return $this->redirect($this->generateUrl('show_event_info', array('id' => $event_id)));
    }

}
