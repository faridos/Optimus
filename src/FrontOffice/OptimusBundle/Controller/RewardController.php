<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Reward;
use FrontOffice\OptimusBundle\Form\RewardType;

/**
 * Reward controller.
 *
 * @Route("/")
 */
class RewardController extends Controller {

    /**
     * Creates a new Reward entity.
     *
     * @Route("profil={id}/récompense/new", name="add_reward_user")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Reward:newRewardUser.html.twig")
     */
    public function addRewardUserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $reward = new Reward();
        $reward->setUser($user);
        $form = $this->createForm(new RewardType(), $reward);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($reward);
            $em->flush();

            return $this->redirect($this->generateUrl('show_profil', array('id' => $id)));
        }

        return array(
            'id' => $id,
            'user' => $user,
            'rewards' => $reward,
            'form' => $form->createView(),
        );
    }
    /**
     * Edits an existing Reward entity.
     *
     * @Route("récompense={id}/modifier", name="reward_user_update")
     * @Method("POST|GET|PUT")
     * @Template("FrontOfficeOptimusBundle:Reward:updateRewardUser.html.twig")
     */
    public function updateRewardUserAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $reward = $em->getRepository('FrontOfficeOptimusBundle:Reward')->find($id);

        if (!$reward) {
            throw $this->createNotFoundException('Unable to find Reward entity.');
        }

        $editForm = $this->createForm(new RewardType(), $reward);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('palmares_user', array('id' => $user->getId())));
        }
        return array(
            'user' => $user,
            'reward' => $reward,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Reward entity.
     *
     * @Route("récompense={id}/supprimer", name="delete_reward_user", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteRewardUserAction($id) {
        $em = $this->getDoctrine()->getManager();
        $reward = $em->getRepository('FrontOfficeOptimusBundle:Reward')->find($id);
        if (!$reward) {
            throw $this->createNotFoundException('Unable to find Reward entity.');
        }
        $em->remove($reward);
        $em->flush();
        $response = new Response($id);
        return $response;
    }

    /**
     * Creates a new Reward entity.
     *
     * @Route("club={id}/récompense/new", name="add_reward_club")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Reward:newRewardclub.html.twig")
     */
    public function addRewardClubAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $reward = new Reward();
        $reward->setClub($club);
        $form = $this->createForm(new RewardType(), $reward);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($reward);
            $em->flush();

           return $this->redirect($this->generateUrl('palmares_club', array('id' => $club->getId())));
        }
        return array(
            'id' => $id,
            'club' => $club,
            'rewards' => $reward,
            'form' => $form->createView(),
        );
    }

    /**
     * Edits an existing Reward entity.
     *
     * @Route("recompenses={id}/modifier", name="reward_club_update")
     * @Method("POST|GET|PUT")
     * @Template("FrontOfficeOptimusBundle:Reward:updateRewardClub.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $reward = $em->getRepository('FrontOfficeOptimusBundle:Reward')->find($id);
        $club = $reward->getClub();
        if (!$reward) {
            throw $this->createNotFoundException('Unable to find Reward entity.');
        }
        $editForm = $this->createForm(new RewardType(), $reward);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

          return $this->redirect($this->generateUrl('palmares_club', array('id' => $club->getId())));
        }
        
        return array(
            'club' => $club,
            'reward' => $reward,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Reward entity.
     *
     * @Route("recompenses={id}/supprimer", name="delete_reward_club", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $reward = $em->getRepository('FrontOfficeOptimusBundle:Reward')->find($id);
        if (!$reward) {
            throw $this->createNotFoundException('Unable to find Reward entity.');
        }
        $em->remove($reward);
        $em->flush();
        $response = new Response($id);
        return $response;
    }

}
