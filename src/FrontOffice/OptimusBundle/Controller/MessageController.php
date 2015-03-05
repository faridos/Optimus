<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Message;
use FrontOffice\OptimusBundle\Form\MessageType;
use FrontOffice\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
/**
 * Message controller.
 *
 * @Route("/message")
 */
class MessageController extends Controller {

    /**
     * Lists all Message entities.
     *
     * @Route("/", name="message")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FrontOfficeOptimusBundle:Message')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Message entity.
     *
     * @Route("/{id}/send/{content}", name="message_send", options={"expose"=true})
     * @Method("GET|POST")
     * @Template("FrontOfficeOptimusBundle:Message:new.html.twig")
     */
    public function createAction($id, $content, Request $request) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $sender = $this->container->get('security.context')->getToken()->getUser();
        $userManager = $this->container->get('fos_user.user_manager');
        $reciever = $userManager->findUserBy(array('id' => $id));
        $message = new Message();
        $message->setReciever($reciever);
        $message->setSender($sender);
        $message->setIsSeen(false);
        $message->setMsgTime(new \DateTime());
        $message->setContent($content);

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $response = new Response();
        $messageJson = json_encode($message);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($messageJson);
        return $response;
    }

    /**
     * Creates a form to create a Message entity.
     *
     * @param Message $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Message $entity) {
        $form = $this->createForm(new MessageType(), $entity, array(
            'action' => $this->generateUrl('message_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Message entity.
     *
     * @Route("/new", name="message_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Message();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Message entity.
     *
     * @Route("/{id}", name="message_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Message')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Message entity.
     *
     * @Route("/{id}/edit", name="message_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Message')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Message entity.
     *
     * @param Message $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Message $entity) {
        $form = $this->createForm(new MessageType(), $entity, array(
            'action' => $this->generateUrl('message_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Message entity.
     *
     * @Route("/{id}", name="message_update")
     * @Method("PUT")
     * @Template("FrontOfficeOptimusBundle:Message:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FrontOfficeOptimusBundle:Message')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('message_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Message entity.
     *
     * @Route("/{id}", name="message_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FrontOfficeOptimusBundle:Message')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Message entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('message'));
    }

    /**
     * Creates a form to delete a Message entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('message_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }
    /**
     * Creates a new Message entity.
     *
     * @Route("/{id}/seen", name="message_seen", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
    public function seenMsgAction($id)
    {
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $message = $em->getRepository('FrontOfficeOptimusBundle:Message')->find($id);
        $message->setIsSeen(true);
        $em->persist($message);
        $em->flush();
        
        $messages = $em->getRepository('FrontOfficeOptimusBundle:Message')->NonseenMsgAjax($user1->getId());
        $response = new Response();
        $messageJson = json_encode($messages);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($messageJson);
        return $response;
    }

}
