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
     * @Route("club={id}/album={ida}/photo", name="photoClub_create")
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

           return $this->redirect($this->generateUrl('photos_club', array('id' => $club->getId(), 'id_album' => $ida)));
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
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $photo = new Photo();
        $form = $this->createForm(new PhotoType(), $photo);
        $form->handleRequest($request);
        $photo->setEvent($event);
    
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
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
     * Deletes a Album entity.
     *
     * @Route("photo={id}/supprimer", name="photo_delete", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction($id) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $photo = $em->getRepository('FrontOfficeOptimusBundle:Photo')->find($id);
        $em->remove($photo);
        $em->flush();
        $response = new Response($id);
         return $response;
    }
    
    /**
     * 
     *
     * @Route("event={id}/paramétres/photo", name="setting_event_photo", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editPhotoEventAction(Request $request, $id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $editForm = $this->createForm(new EventPhotoType(), $entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('show_event', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Photo:editPhotoEvent.html.twig', array('form' => $editForm->createView()));
    }
}
