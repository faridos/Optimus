<?php

namespace FrontOffice\OptimusBundle\Controller;

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
     * @Route("club={id}/album={ida}/photo", name="photoClub_create")
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Club:newPhotoClub.html.twig")
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

            die('ok');
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

            return $this->redirect($this->generateUrl('photos_event', array('id' => $id)));
        }
        
        return array(
            
            'photo' => $photo,
            'id' => $event->getId(),
            'event' => $event,
            'form'   => $form->createView(),
        );
    }

}
