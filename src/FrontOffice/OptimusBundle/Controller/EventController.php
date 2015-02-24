<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Entity\Event;
use FrontOffice\OptimusBundle\Form\EventType;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\Event\ParticipationEventEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

class EventController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findBy(array('active' => 1));
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        //$x=$em->getRepository("FrontOfficeOptimusBundle:Event")->get_distance_m(48.856667,2.350987, 45.767299, 4.834329);
        return $this->render('FrontOfficeOptimusBundle:Event:index.html.twig', array('events' => $events));
    }

    public function addAction() {
        $em = $this->getDoctrine()->getManager();
        //$x=$em->getRepository("FrontOfficeOptimusBundle:Event")->get_distance_m(48.856667,2.350987, 45.767299, 4.834329);
        $user = $this->container->get('security.context')->getToken()->getUser();

        $event = new Event;
        $event->setCreateur($user);
        $event->setDateModification(null);
        $event->setActive(true);
        $form = $this->createForm(new EventType, $event);
        $req = $this->get('request');
        if ($req->getMethod() == "POST") {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($event);
                $em->flush();
                //add notification + add prticipation + add History
                $eventhistory = new HistoryEventEvent($user, $event);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Event:new.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    public function showInfoAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventNotif($id);
        if (is_object($user)) {
            if (!$event) {
                throw $this->createNotFoundException('Event Annulé.');
            }
            $notificationdelete = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('event' => $event, 'type' => 'delete'));
            $notificationupdate = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('event' => $event, 'type' => 'Update'));
            $notificationadd = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('event' => $event, 'type' => 'add'));
            if ($notificationadd) {
                $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notificationadd));
                if (empty($notificationSeen)) {
                    $NotificationSeen = new NotificationSeen();
                    $NotificationSeen->setNotifications($notificationadd);
                    $NotificationSeen->setUsers($user);
                    $em->persist($NotificationSeen);
                    $em->flush();
                }
            }
            if ($notificationupdate) {
                $participation = $em->getRepository('FrontOfficeOptimusBundle:Notification')->getParicipationNotification($user->getId(), $event[0]->getId(), $notificationupdate->getDatenotification());
                if ($participation) {
                    $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notificationupdate));
                    if (empty($notificationSeen)) {
                        $NotificationSeen = new NotificationSeen();
                        $NotificationSeen->setNotifications($notificationupdate);
                        $NotificationSeen->setUsers($user);
                        $em->persist($NotificationSeen);
                        $em->flush();
                    }
                }
            }
            if ($notificationdelete) {
                $participation = $em->getRepository('FrontOfficeOptimusBundle:Notification')->getParicipationNotification($user->getId(), $event[0]->getId(), $notificationdelete->getDatenotification());
                if ($participation) {
                    $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notificationdelete));
                    if (empty($notificationSeen)) {
                        $NotificationSeen = new NotificationSeen();
                        $NotificationSeen->setNotifications($notificationdelete);
                        $NotificationSeen->setUsers($user);
                        $em->persist($NotificationSeen);
                        $em->flush();
                    }
                }
            }
        }
        $nb_participants = count($em->getRepository("FrontOfficeOptimusBundle:Participation")->findBy(array("event" => $event)));
        return $this->render('FrontOfficeOptimusBundle:Event:showInfo.html.twig', array('entity' => $event[0],
                    'nb_participants' => $nb_participants,
                    'user' => $user));
    }

    public function showMurAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
        return $this->render('FrontOfficeOptimusBundle:Event:showMur.html.twig', array('entity' => $event, 'UsersInvitations' => @$UsersInvitations, 'Invitations' => @$Invitations, 'friends' => @$Friends, 'user' => @$user, 'count' => @$count));
    }

    public function showPhotosAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
        $albums = $em->getRepository('FrontOfficeOptimusBundle:Album')->findBy(array('event' => $event));
        $nb_participants = count($em->getRepository("FrontOfficeOptimusBundle:Participation")->findBy(array("event" => $event)));
        return $this->render('FrontOfficeOptimusBundle:Event:showPhotos.html.twig', array('albums' => $albums, 'entity' => $event, 'nb_participants' => $nb_participants, 'UsersInvitations' => @$UsersInvitations, 'Invitations' => @$Invitations, 'friends' => @$Friends, 'user' => @$user, 'count' => @$count));
    }

    public function showParticipantsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
        $participants = $em->getRepository("FrontOfficeOptimusBundle:Event")->getParticipants($event);
        $nb_participants = count($em->getRepository("FrontOfficeOptimusBundle:Participation")->findBy(array("event" => $event)));
        return $this->render('FrontOfficeOptimusBundle:Event:showParticipants.html.twig', array('entity' => $event, 'participants' => $participants, 'nb_participants' => $nb_participants, 'UsersInvitations' => @$UsersInvitations, 'Invitations' => @$Invitations, 'friends' => @$Friends, 'user' => @$user, 'count' => @$count));
    }

    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        $editForm = $this->createEditForm($entity);
        return $this->render('FrontOfficeOptimusBundle:Event:edit.html.twig', array(
                    'user' => $user,
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    private function createEditForm(Event $entity) {
        $form = $this->createForm(new EventType(), $entity
        );
        return $form;
    }

    public function updateAction(Request $request, $id) {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $entity->setDateModification(new DateTime());
            $em->flush();
            //add notification
            $notification = new Notification();
            $notification->setEvent($entity);
            $notification->setDatenotification(new DateTime());
            $notification->setType('Update');
            $notification->setNotificateur($user->getId());
            $em->persist($notification);
            $em->flush();
            // add History 
            $history = new HistoryEvent();
            $history->setAction("Mise à jour");
            $history->setEvent($entity);
            $history->setUser($user);
            $em->persist($history);
            $em->flush();
            return $this->redirect($this->generateUrl('show_event_info', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Event:edit.html.twig', array(
                    'user' => $user,
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    public function deleteAction($id) {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();
        //add notification delete
        $notification = new Notification();
        $notification->setEvent($entity);
        $notification->setDatenotification(new DateTime());
        $notification->setType('delete');
        $notification->setNotificateur($user->getId());
        $em->persist($notification);
        $em->flush();
        // add History 
        $history = new HistoryEvent();
        $history->setAction("Suppression");
        $history->setEvent($entity);
        $history->setUser($user);
        $em->persist($history);
        $em->flush();
        return $this->redirect($this->generateUrl('user_schow', array('id' => $user->getId())));
    }

    public function annulerAction($id) {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('event' => $event));
        if ($notification) {
            $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notification));
            if (empty($notificationSeen)) {
                $NotificationSeen = new NotificationSeen();
                $NotificationSeen->setNotifications($notification);
                $NotificationSeen->setUsers($user);
                $em->persist($NotificationSeen);
                $em->flush();
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Event:annuler.html.twig');
    }

    public function competitionsAction() {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository("FrontOfficeOptimusBundle:TypeEvent")->findBy(array('nom' => 'compétition'));
        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findBy(array('type' => $competition[0], 'active' => 1));
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        //les particpiations d'utilisateur courant
        $user_participations = $em->getRepository("FrontOfficeOptimusBundle:Participation")->findByParticipant($user);
        //get invitations
        $UsersInvitations = $em->getRepository('FrontOfficeUserBundle:User')->getUsersInvitations($user->getId());
        $Invitations = $em->getRepository('FrontOfficeUserBundle:User')->getInvitations($user->getId());
        //return page twig avec variables
        $count = (count($Invitations));
        return $this->render('FrontOfficeOptimusBundle:Event:competitions.html.twig', array(
                    'count' => $count,
                    'events' => $events,
                    'user' => $user,
                    'UsersInvitations' => $UsersInvitations,
                    'Invitations' => $Invitations,
                    'participations' => $user_participations));
    }

    public function CoordonneEventAction($lng, $lat) {
        $em = $this->getDoctrine()->getEntityManager();
        $events = new ArrayCollection();

        $events = $em->getRepository('FrontOfficeOptimusBundle:Event')->EventLoad(new DateTime(), $lng, $lat);
        if (!$events) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $response = new Response();
        $tabevents = json_encode($events);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($tabevents);
        return $response;
    }

}
