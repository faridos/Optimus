<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Comment;
use FrontOffice\OptimusBundle\Repository\CommentRepository;
use FrontOffice\OptimusBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use \DateTime;

/**
 * Comment controller.
 *
 * @Route("/")
 * 
 */
class CommentController extends Controller
{

    
 /**
 *
 * @Route("/comment/event={eventid}", name="ajax_event_comment", options={"expose"=true})
 * @Method("POST")
 * 
 */
public function ajaxCommenteventAction(Request $request, $eventid) {
        $comment = new Comment();
        $em = $this->getDoctrine()->getManager();
        $commenteur = $this->container->get('security.context')->getToken()->getUser();
        $commenteurId = $commenteur->getId();
        $commentor = $em->getRepository('FrontOfficeUserBundle:User')->find($commenteurId);

        if ($request->isXmlHttpRequest()) {
            $content = $this->get("request")->getContent();
            $cntnt = rawurldecode(urldecode($content));
            $vars = explode('=', $cntnt);
            $cmt = $vars[1];
            $event = $em->getRepository('FrontOfficeOptimusBundle:event')->find($eventid);
            $comment->setCommenteur($commentor);
            $comment->setEvent($event);
            $comment->setCommentaire($cmt);
            $comment->setCreatedat(new \Datetime());
            $em->persist($comment);
            $em->flush();
        }
        
        return $response = new Response();
    }

}
