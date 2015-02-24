<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NotificationSeenRepository extends EntityRepository
{
     public function getNotificationSeen($id)
   {
        $em = $this->getEntityManager();
        $qb = $em->createQuery("select n.id "
                . "  from FrontOfficeOptimusBundle:NotificationSeen ns "
                . "LEFT JOIN ns.notifications n "
                . "where ns.users = :id  "
                )
           ->setParameter('id', $id);
        return $qb->getResult();
   }
}
