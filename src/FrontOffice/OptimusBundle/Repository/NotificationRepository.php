<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository {

    public function getNotification($id, $date) {
        $em = $this->getEntityManager();
        $user = $em->getRepository("FrontOfficeUserBundle:User")->find($id);
        $type = $user->getTypeNotification();
        switch ($type) {
            case 'NOT':
                break;
            case 'All':

                $qb = $em->createQuery("select n "
                                . "  from FrontOfficeOptimusBundle:Notification n "
                                . " where n.datenotification > :date "
                                . " and  n.notificateur != :id "
                                . " ORDER BY n.datenotification DESC"
                        )
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();

                break;
            case 'E':
                $em = $this->getEntityManager();
                $qb = $em->createQuery("select n"
                                . "  from FrontOfficeOptimusBundle:Notification n "
                                . "LEFT JOIN n.event e "
                                . " where n.event IS NOT NULL "
                                . " and n.datenotification > :date "
                                . " and n.notificateur != :id  "
                                . " ORDER BY n.datenotification DESC"
                        )
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();
                break;
            case 'U':
                $em = $this->getEntityManager();
                $qb = $em->createQuery("select n, e, c, u"
                                . "  from FrontOfficeOptimusBundle:Notification n "
                                . "LEFT JOIN n.event e "
                                . "LEFT JOIN n.club c "
                                . "LEFT JOIN n.entraineur u "
                                . "where n.entraineur  IS NOT NULL "
                                . " and n.datenotification > :date "
                                . " and n.notificateur != :id"
                                . " ORDER BY n.datenotification DESC")
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();
                break;
            case 'C':
                $em = $this->getEntityManager();
                $qb = $em->createQuery("select n, e, c, u"
                                . "  from FrontOfficeOptimusBundle:Notification n "
                                . "LEFT JOIN n.event e "
                                . "LEFT JOIN n.club c "
                                . "LEFT JOIN n.entraineur u "
                                . "where n.club IS NOT NULL "
                                . " and n.datenotification > :date "
                                . " and n.notificateur != :id  "
                                . " ORDER BY n.datenotification DESC")
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();
                break;
            case 'UC':
                $em = $this->getEntityManager();
                $qb = $em->createQuery("select n, e, c, u"
                                . "  from FrontOfficeOptimusBundle:Notification n "
                                . "LEFT JOIN n.event e "
                                . "LEFT JOIN n.club c "
                                . "LEFT JOIN n.entraineur u "
                                . "where n.notificateur != :id  "
                                . " and n.club IS NOT NULL  OR n.entraineur IS NOT NULL "
                                . " and n.datenotification > :date  "
                                . " ORDER BY n.datenotification DESC ")
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();
                break;
            case 'EU':
                $em = $this->getEntityManager();
                $qb = $em->createQuery("select n, e, c, u"
                                . "  from FrontOfficeOptimusBundle:Notification n "
                                . "LEFT JOIN n.event e "
                                . "LEFT JOIN n.club c "
                                . "LEFT JOIN n.entraineur u "
                                . " where n.entraineur IS NOT NULL OR n.event IS NOT NULL  "
                                . " and n.datenotification > :date "
                                . " and n.notificateur != :id  "
                                . " ORDER BY n.datenotification DESC ")
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();
                break;
            case 'EC':
                $em = $this->getEntityManager();
                $qb = $em->createQuery("select n"
                                . " from FrontOfficeOptimusBundle:Notification n "
                                . " LEFT JOIN n.event e "
                                . " LEFT JOIN n.club c "
                                . " LEFT JOIN n.entraineur u "
                                . " where n.club IS NOT NULL OR n.event IS NOT NULL   "
                                . " and  n.notificateur != :id   and n.datenotification > :date  "
                                . " ORDER BY n.datenotification DESC ")
                        ->setParameter('date', $date->format('Y-m-d H:i:s'))
                        ->setParameter('id', $id);
                return $qb->getResult();
                break;
            default:
        }
    }

    public function getNbNotification($id, $date) {
        $em = $this->getEntityManager();
        $user = $em->getRepository("FrontOfficeUserBundle:User")->find($id);
        $type = $user->getTypeNotification();
        $notifications = $this->getNotification($id, $date);
        if ($notifications) {

            switch ($type) {
                case 'NOT':
                    break;
                case 'All':


                    foreach ($notifications as $notif) {
                        $participation = $em->getRepository('FrontOfficeOptimusBundle:Participation')->getParicipationNotification($id, $notif->getEvent(), $notif->getDatenotification());
                    }
                    if ($participation) {
                        $em = $this->getEntityManager();
                        $qb = $em->createQuery("select n.id "
                                        . "  from FrontOfficeOptimusBundle:Notification n "
                                        . " where n.notificateur != :id and n.datenotification > :date  "
                                )
                                ->setParameter('date', $date->format('Y-m-d H:i:s'))
                                ->setParameter('id', $id);
                        return $qb->getResult();
                    } else {
                        $em = $this->getEntityManager();
                        $qb = $em->createQuery(" select n.id "
                                        . "  from FrontOfficeOptimusBundle:Notification n "
                                        . " where n.notificateur != :id and n.datenotification > :date  and  n.type = 'add' "
                                )
                                ->setParameter('date', $date->format('Y-m-d H:i:s'))
                                ->setParameter('id', $id);
                        return $qb->getResult();
                    }

                    break;
                case 'E':
                    foreach ($notifications as $notif) {
                        $participation = $em->getRepository('FrontOfficeOptimusBundle:Participation')->getParicipationNotification($id, $notif->getEvent(), $notif->getDatenotification());
                    }
                    if ($participation) {
                        $em = $this->getEntityManager();
                        $qb = $em->createQuery("select n.id "
                                        . " from FrontOfficeOptimusBundle:Notification n "
                                        . " LEFT JOIN n.event e "
                                        . " where n.event IS NOT NULL  "
                                        . " and n.notificateur != :id and n.datenotification > :date "
                                )
                                ->setParameter('date', $date->format('Y-m-d H:i:s'))
                                ->setParameter('id', $id);
                        return $qb->getResult();
                    } else {
                        $em = $this->getEntityManager();
                        $qb = $em->createQuery("select n.id "
                                        . "  from FrontOfficeOptimusBundle:Notification n "
                                        . "LEFT JOIN n.event e "
                                        . "where n.notificateur != :id and n.datenotification > :date "
                                        . " and n.type = 'add' "
                                        . " and n.event IS NOT NULL "
                                )
                                ->setParameter('date', $date->format('Y-m-d H:i:s'))
                                ->setParameter('id', $id);
                        return $qb->getResult();
                    }

                    break;
                case 'U':
                    $em = $this->getEntityManager();
                    $qb = $em->createQuery("select n.id"
                                    . "  from FrontOfficeOptimusBundle:Notification n "
                                    . "LEFT JOIN n.entraineur u "
                                    . "where n.entraineur  IS NOT NULL and n.datenotification > :date "
                                    . "and n.notificateur != :id"
                            )
                            ->setParameter('date', $date->format('Y-m-d H:i:s'))
                            ->setParameter('id', $id);
                    return $qb->getResult();
                    break;
                case 'C':
                    $em = $this->getEntityManager();
                    $qb = $em->createQuery("select n.id"
                                    . "  from FrontOfficeOptimusBundle:Notification n "
                                    . "LEFT JOIN n.club c "
                                    . "where n.club IS NOT NULL and n.datenotification > :date "
                                    . "and n.notificateur != :id  "
                            )
                            ->setParameter('date', $date->format('Y-m-d H:i:s'))
                            ->setParameter('id', $id);
                    return $qb->getResult();
                    break;
                case 'UC':
                    $em = $this->getEntityManager();
                    $qb = $em->createQuery("select n.id "
                                    . "  from FrontOfficeOptimusBundle:Notification n "
                                    . "LEFT JOIN n.club c "
                                    . "LEFT JOIN n.entraineur u "
                                    . " where (n.entraineur IS NOT NULL OR n.club IS NOT NULL)"
                                    . " and n.datenotification > :date "
                                    . "and  n.notificateur != :id  "
                            )
                            ->setParameter('date', $date->format('Y-m-d H:i:s'))
                            ->setParameter('id', $id);
                    return $qb->getResult();
                    break;
                case 'EU':
                    break;
                case 'EC':
                    break;
            }
        }
    }

}
