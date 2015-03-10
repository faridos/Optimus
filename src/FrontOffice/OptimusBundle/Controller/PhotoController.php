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
 * @Route("/photo")
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
}
