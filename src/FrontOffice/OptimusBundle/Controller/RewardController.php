<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
class RewardController extends Controller
{

    /**
     * Creates a new Reward entity.
     *
     * @Route("profil={id}/récompense/new", name="add_reward_user")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Reward:newRewardUser.html.twig")
     */
    public function addRewardUserAction(Request $request,$id)
    {
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

           die('ok reward');
        }

        return array(
            'id'   => $id,
            'user' =>   $user ,
            'rewards' => $reward,
            'form'   => $form->createView(),
        );
    }
}
