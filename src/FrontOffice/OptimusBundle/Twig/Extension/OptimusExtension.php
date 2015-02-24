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
            new \Twig_SimpleFunction('getNombreNotification', array($this, 'getNombreNotification'))
            );
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