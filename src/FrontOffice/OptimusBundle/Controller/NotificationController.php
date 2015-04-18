<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Event\NotificationSeenEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\UserBundle\Entity\User;
use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Entity\NotificationSeen;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Notification controller.
 *
 * @Route("/notification")
 */
class NotificationController extends Controller {

    /**
     * 
     *
     * @Route("/participe", name="participe_notification", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function participeNotifAction() {
        $c = 0;
        $res = array();
        $resjour = array();
        $resCompjour = array();
        $tab1 = array("participation", "AnnulerParticip", "update", "delete", "comment", "photo");
        //$notifsParticip = new ArrayCollection();
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $amis = $em->getRepository('FrontOfficeUserBundle:User')->getFrinds($user->getId());

        $eventjours = $em->getRepository('FrontOfficeOptimusBundle:Event')->getEventJour();
        $competitionjours = $em->getRepository('FrontOfficeOptimusBundle:Competition')->getCompetitionJour();

        foreach ($eventjours as $eventjour) {
            foreach ($user->getParticipations() as $participe) {
                if ($participe->getEvent()->getId() == $eventjour->getId()) {
                    $resjour[] = $eventjour;
                }
            }
        }
        foreach ($competitionjours as $competitionjour) {
            foreach ($user->getAdherent() as $membre) {
                foreach ($membre->getParticips() as $participe) {
                    if ($participe->getParticips()->getCompetition()->getId() == $competitionjour->getId()) {
                        $resCompjour[] = $competitionjour;
                    }
                }
            }
        }

        if ($user->getConfigNotif()->getEvent()) {
            foreach ($amis as $ami) {
                $isAmi = $em->getRepository('FrontOfficeUserBundle:User')->getIsAmis($user->getId(), $ami->getId());
                foreach ($ami->getNotificateur() as $notif) {
                    if ($notif->getType() == "add" && $notif->getDatenotification() > $isAmi[0]->getConfirmedAt() && $notif->getDatenotification() > $user->getConfigNotif()->getDateModifEvent()) {
                        $i = 0;

                        foreach ($user->getNotificationseen() as $notifSeen) {
                            if ($notifSeen->getNotifications()->getId() == $notif->getId()) {
                                $i = 1;
                            }
                        }
                        if ($i == 0) {

                            $res[$c] = $notif;
                            $c++;
                        }
                    }
                }
            }

            foreach ($user->getParticipations() as $participe) {

                foreach ($participe->getEvent()->getNotificationEvent() as $notif2) {
                    if ($user->getId() != $notif2->getNotificateur()->getId() && in_array($notif2->getType(), $tab1) && $notif2->getDatenotification() > $participe->getDatePaticipation() && $notif2->getDatenotification() > $user->getConfigNotif()->getDateModifEvent()) {
                        $i = 0;
                        foreach ($user->getNotificationseen() as $notifSeen) {
                            if ($notifSeen->getNotifications()->getId() == $notif2->getId()) {
                                $i = 1;
                            }
                        }
                        if ($i == 0) {

                            $res[$c] = $notif2;
                            $c++;
                        }
                    }
                }
            }
        }

        if ($user->getConfigNotif()->getEntraineur()) {
            $notificationentrain = $em->getRepository('FrontOfficeOptimusBundle:Notification')->getlisteEntraineur($user->getId());
            foreach ($notificationentrain as $val) {

                if ($user->getcreatedAt() < $val->getEntraineur()->getcreatedAt() && $val->getEntraineur()->getId() != $user->getId() && $notif->getDatenotification() > $user->getConfigNotif()->getDateModifEntraineur()) {
                    $i = 0;
                    foreach ($user->getNotificationseen() as $notifSeen) {
                        if ($notifSeen->getNotifications()->getId() == $val->getId()) {
                            $i = 1;
                        }
                    }

                    if ($i == 0) {

                        $res[$c] = $val;
                        $c++;
                    }
                }
            }
        }

        $notificationstars = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findBy(array("idVote" => "U" . $user->getId()));
        foreach ($notificationstars as $notifstar) {
            $i = 0;
            foreach ($user->getNotificationseen() as $notifSeen) {
                if ($notifSeen->getNotifications()->getId() == $notifstar->getId()) {
                    $i = 1;
                }
            }
            if ($i == 0) {
                $res[$c] = $notifstar;
                $c++;
            }
        }

        $notifAcceptRefuse = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findBy(array("entraineur" => $user), array("datenotification" => 'DESC'));
        foreach ($notifAcceptRefuse as $notifAR) {
            if ($notifAR->getType() == "accepte" || $notifAR->getType() == "refuse") {
                $i = 0;
                foreach ($user->getNotificationseen() as $notifSeen) {
                    if ($notifSeen->getNotifications()->getId() == $notifAR->getId()) {
                        $i = 1;
                    }
                }
                if ($i == 0) {
                    $res[$c] = $notifAR;
                    $c++;
                }
            }
        }

        if ($user->getConfigNotif()->getClub()) {
            $notifClubRejs = $em->getRepository('FrontOfficeOptimusBundle:Notification')->getNotifEntraineur($user->getId());

            foreach ($notifClubRejs as $notifClubRej) {
                if ($notifClubRej->getDatenotification() > $user->getConfigNotif()->getDateModifClub()) {
                    $i = 0;
                    foreach ($user->getNotificationseen() as $notifSeen) {
                        if ($notifSeen->getNotifications()->getId() == $notifClubRej->getId()) {
                            $i = 1;
                        }
                    }
                    if ($i == 0) {
                        $res[$c] = $notifClubRej;
                        $c++;
                    }
                }
            }


            $notifAddClubs = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findBy(array("type" => "addClub"), array("datenotification" => 'DESC'));
            foreach ($notifAddClubs as $notifAddClub) {
                if ($notifAddClub->getDatenotification() > $user->getConfigNotif()->getDateModifClub()) {
                    $i = 0;
                    foreach ($user->getNotificationseen() as $notifSeen) {
                        if ($notifSeen->getNotifications()->getId() == $notifAddClub->getId()) {
                            $i = 1;
                        }
                    }
                    if ($i == 0) {
                        $res[$c] = $notifAddClub;
                        $c++;
                    }
                }
            }
        }


        foreach ($res as $val) {
            $notifseen = new NotificationSeen();
            $notifseen->setUsers($user);
            $notifseen->setNotifications($val);
            $em->persist($notifseen);
            $em->flush();
        }
        $notificationnonvu = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findBy(array("users" => $user->getId(), "vu" => 0));
        $datenotificationseen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findBy(array("users" => $user->getId()), array("datenotificationseen" => 'DESC'));

        return $this->render('FrontOfficeOptimusBundle:Notification:notifParticipe.html.twig', array('datenotificationseen' => $datenotificationseen, 'count' => $notificationnonvu, 'res' => $res, 'resjour' => $resjour,'resCompjour'=>$resCompjour));
    }

}
