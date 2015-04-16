<?php

namespace FrontOffice\OptimusBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Photo;
use FrontOffice\OptimusBundle\Form\PhotoType;
use FrontOffice\OptimusBundle\Entity\Notification;

/**
 * Photo controller.
 *
 * @Route("/")
 */
class PhotoController extends Controller
{
     /**
     * Creates a new Photo entity.
     *
     * @Route("profil={id}/album={ida}/photo", name="photoProfil_create")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Photo:newPhotoProfil.html.twig")
     */
    public function createPhotoProfilAction(Request $request,$ida)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($ida);
        $entity = new Photo();
        $form = $this->createForm(new PhotoType(), $entity);
        $form->handleRequest($request);
        $entity->setAlbum($album);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

           return $this->redirect($this->generateUrl('show_profil', array('id' => $user->getId())));
        }
        
        return array(
            
            'entity' => $entity,
            'user' => $user,
            'ida' => $album->getId(),
            'form'   => $form->createView(),
        );
    }

   /**
     * Creates a new Photo entity.
     *
     * @Route("album={ida}/photo", name="photoClub_create")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Photo:newPhotoClub.html.twig")
     */
    public function createPhotoClubAction(Request $request,$ida)
    {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($ida);
        $club = $album->getClub();
        $entity = new Photo();
        $form = $this->createForm(new PhotoType(), $entity);
        $form->handleRequest($request);
        $entity->setAlbum($album);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

           return $this->redirect($this->generateUrl('show_club', array('id' => $club->getId())));
        }
        
        return array(
            
            'entity' => $entity,
            'id' => $club->getId(),
            'ida' => $album->getId(),
            'form'   => $form->createView(),
        );
    }
     /**
     * Creates a new Photo entity.
     *
     * @Route("evenement={id}/photo/ajouter", name="photoEvent_create")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Photo:newPhotoEvent.html.twig")
     */
    public function createPhotoEventAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $photo = new Photo();
        $form = $this->createForm(new PhotoType(), $photo);
        $form->handleRequest($request);
        $photo->setEvent($event);
        $photo->setUser($user);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
            
            $notif = new Notification();
            $notif->setNotificateur($user);
            $notif->setType('photo');
            $notif->setEvent($event);
            $em->persist($notif);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('AjouterPohoto', "Pohoto a été ajouter avec success.");
            return $this->redirect($this->generateUrl('show_event', array('id' => $id)));
        }
        
        return array(
            
            'photo' => $photo,
            'id' => $event->getId(),
            'event' => $event,
            'form'   => $form->createView(),
        );
    }
    /**
     * Creates a new Photo entity.
     *
     * @Route("competition/{id}/photo", name="photoCompetition_create")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Photo:newPhotoCompetition.html.twig")
     */
    public function createPhotoCompetitionAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $photo = new Photo();
        $form = $this->createForm(new PhotoType(), $photo);
        $form->handleRequest($request);
        $photo->setCompetition($competition);
        $photo->setUser($user);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
 $request->getSession()->getFlashBag()->add('AjouterPohoto', "Pohoto a été ajouter avec success.");
           return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
        }
        
        return array(
            
            'photo' => $photo,
            'id' => $id,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Deletes a Album entity.
     *
     * @Route("photo={id}/supprimer", name="photo_delete", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request,$id) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $photo = $em->getRepository('FrontOfficeOptimusBundle:Photo')->find($id);
        $em->remove($photo);
        $em->flush();
         $request->getSession()->getFlashBag()->add('SuppPohoto', "Pohoto a été supprimée.");

        $response = new Response($id);
         return $response;
    }
    
 
}
