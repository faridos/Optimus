<?php

namespace FrontOffice\OptimusBundle\Twig\Extension;

class OptimusExtension extends \Twig_Extension {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('getNotifications', array($this, 'getNotifications')),
            new \Twig_SimpleFunction('getNombreNotification', array($this, 'getNombreNotification')),
            new \Twig_SimpleFunction('pendingInvitation', array($this, 'pendingInvitation')),
            new \Twig_SimpleFunction('getFriends', array($this, 'getFriends')),
            new \Twig_SimpleFunction('getUser', array($this, 'getUser')),
            new \Twig_SimpleFunction('getUsersInvitations', array($this, 'getUsersInvitations')),
            new \Twig_SimpleFunction('getInvitations', array($this, 'getInvitations')),
            new \Twig_SimpleFunction('getParicipationEventNotification', array($this, 'getParicipationEventNotification')),
            new \Twig_SimpleFunction('getNonSeenMessage', array($this, 'getNonSeenMessage')),
            new \Twig_SimpleFunction('getMembreRequest', array($this, 'getMembreRequest')),
            new \Twig_SimpleFunction('getMembreConfirmed', array($this, 'getMembreConfirmed')),
        );
    }

    public function getMembreRequest($user, $club) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Member')->findBy(array('member' => $user, 'clubad' => $club));
    }

    public function getMembreConfirmed($user, $club) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Member')->findBy(array('member' => $user, 'clubad' => $club, 'confirmed' => '1'));
    }

    public function getNonSeenMessage($id) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Message')->NonseenMsg($id);
    }

    public function getUsersInvitations($id) {
        return $this->em->getRepository('FrontOfficeUserBundle:User')->find($id);
    }

    public function getInvitations($id) {

        return $this->em->getRepository('FrontOfficeUserBundle:User')->getInvitations($id);
    }

    public function getParicipationEventNotification($id, $date) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Participation')->getParicipationEventNotification($id, $date);
    }

    public function getUser($id) {
        return $notificateur = $this->em->getRepository('FrontOfficeUserBundle:User')->find($id);
    }

    public function getFriends($id) {
        $Friends = $this->em->getRepository('FrontOfficeUserBundle:User')->getFrinds($id);
//        $entities2 = $this->em->getRepository('FrontOfficeUserBundle:User')->getLeftFrinds($id);
//        $Friends = array_merge($entities1, $entities2);
        return $Friends;
    }

    public function pendingInvitation($user, $user1) {
        $pendingInvitations = $this->em->getRepository('FrontOfficeUserBundle:User')->getpendingInvitations($user->getId(), $user1->getId());
        return $pendingInvitations;
    }

    public function getNotifications($id, $date) {

        $notifications = $this->em->getRepository('FrontOfficeOptimusBundle:Notification')->getNotification($id, $date);
        return $notifications;
    }

    public function getNombreNotification($id, $date) {
        $nombre = array();
        $notification = $this->em->getRepository('FrontOfficeOptimusBundle:Notification')->getNbNotification($id, $date);
        $notificationseen = $this->em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->getNotificationSeen($id);
        if ($notification) {
            foreach ($notification as $v1) {
                $t1[] = $v1['id'];
            }
            if ($notificationseen) {
                foreach ($notificationseen as $v2) {
                    $t2[] = $v2['id'];
                }
                $nombre = array_diff($t1, $t2);
            } else {
                $t2 = array();
                $nombre = array_diff($t1, $t2);
            }
        }
        return $nombre;
    }

    public function getName() {
        return 'optimus_extension';
    }

}
