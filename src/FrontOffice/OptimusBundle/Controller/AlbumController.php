<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Album;
use FrontOffice\OptimusBundle\Form\AlbumType;
use \DateTime;
/**
 * Album controller.
 *
 * @Route("/")
 */
class AlbumController extends Controller
{

    /**
     * Creates a new Album entity.
     *
     * @Route("/profil={id}/album/ajouter", name="new_album_profil")
     * 
     * @Template("FrontOfficeOptimusBundle:Album:new.html.twig")
     */
    public function createAlbumUserAction(Request $request) {
        $entity = new Album();
        $date = new DateTime();
        $entity->setCreatedate($date);
        $entity->setUpdatedate($date);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity->setUser($user);
        $form = $this->createForm(new AlbumType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            die('ok samir');
        }
        return array(
            'user' => $user,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }
    /**
     * Lists all Album entities.
     *
     * @Route("profil={id}/albums", name="album")
     * @Method("GET")
     * @Template()
     */
    public function albumUserAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $albums = $em->getRepository('FrontOfficeOptimusBundle:Album')->findBy(array('utilisateur' => $user));
        return $this->render('FrontOfficeOptimusBundle:Album:albumProfil.html.twig', array(
                    'id' => $id,
                    'user' => $user,
                    'albums' => $albums,
                    'user1' => $user1,
        ));
    }
}
