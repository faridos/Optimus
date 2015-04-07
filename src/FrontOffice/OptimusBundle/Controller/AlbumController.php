<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Album;
use FrontOffice\OptimusBundle\Form\AlbumType;
use Symfony\Component\HttpFoundation\Response;
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
     * @Template("FrontOfficeOptimusBundle:Album:newAlbumUser.html.twig")
     */
    public function createAlbumUserAction(Request $request,$id) {
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
            $request->getSession()->getFlashBag()->add('AjouterAlbum', "Album  a été creé avec success.");
          return $this->redirect($this->generateUrl('show_profil', array('id' => $id)));  
        }
        return array(
            'user' => $user,
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }
    /**
     * Creates a new Album entity.
     *
     * @Route("/club={id}/albumc/new/", name="new_album_club")
     * 
     * @Template("FrontOfficeOptimusBundle:Album:newAlbumClub.html.twig")
     */
    public function createAlbumClubAction(Request $request, $id) {
        $entity = new Album();
        $date = new DateTime();
        $entity->setCreatedate($date);
        $entity->setUpdatedate($date);
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $entity->setClub($club);
        $form = $this->createForm(new AlbumType(), $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
           
            $em->persist($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('AjouterAlbumClub', "Album  a été creé avec success.");
            return $this->redirect($this->generateUrl('show_club', array('id' => $id))); 
        }

        return array(
            'club' => $club,
            'album' => $entity,
            'form' => $form->createView(),
        );
    }
    /**
     * Displays a form to edit an existing Album entity.
     *
     * @Route("album={id}/modifier", name="album_edit")
     * @Method("POST|GET|PUT")
     * @Template("FrontOfficeOptimusBundle:Album:edit.html.twig")
     */
    public function editAlbumAction(Request $request, $id) {
           $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($id);
        if (!$album) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }
        $editForm = $this->createForm(new AlbumType(), $album);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
             $request->getSession()->getFlashBag()->add('modificationAlbum', "Album  a été Modifier.");
            return $this->redirect($this->generateUrl('show_profil', array('id' => $user->getId())));
        }
        return array(
           
            'album' => $album,
            'edit_form' => $editForm->createView(),
        );
    }
     /**
     * Displays a form to edit an existing Album entity.
     *
     * @Route("/club/{id_club}/album/{id}/modifier", name="album_club_edit")
     * @Method("POST|GET|PUT")
     * @Template("FrontOfficeOptimusBundle:Album:editAlbumClub.html.twig")
     */
    public function editAlbumClubAction(Request $request,$id_club, $id) {
          
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($id);
        if (!$album) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }
        $editForm = $this->createForm(new AlbumType(), $album);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('show_club', array('id' => $id_club)));
        }
        return array(
            'id_club' => $id_club,
            'album' => $album,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Album entity.
     *
     * @Route("album={id}/supprimer", name="album_delete", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request,$id) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($id);
        $em->remove($entity);
        $em->flush();
        $request->getSession()->getFlashBag()->add('supprissionAlbum', "Album  a été supprimer.");
        $response = new Response($id);
         return $response;
    }
   
}
