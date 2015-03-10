<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository {

    public function getEventLoad($date, $lng, $lat) {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT event, u "
                        . "FROM FrontOfficeOptimusBundle:Event event LEFT JOIN event.createur u"
                        . " where event.active = 1 and event.dateFin > :date and event.lng BETWEEN :lng-0.1 AND :lng+0.1 and event.lat BETWEEN :lat-0.1 AND :lat+0.1"
                        . " ORDER BY event.dateDebut DESC "
                )->setParameter('date', $date)
                ->setParameter('lng', $lng)
                ->setParameter('lat', $lat);

        return $events = $query->getResult();
    }
    
    public function getEventsMap($date) {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT event, u "
                        . "FROM FrontOfficeOptimusBundle:Event event LEFT JOIN event.createur u"
                        . " where event.active = 1 and event.dateFin > :date"
                )->setParameter('date', $date);

        return $events = $query->getResult();
    }
    
    public function getParticipants($event) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $particip = $qb->select('p')
                        ->from("FrontOfficeOptimusBundle:Participation", 'p')
                        ->where("p.event = :event")
                        ->setParameter('event', $event)
                        ->getQuery()->getResult();

        $participants = array();

        foreach ($particip as $p) {
            $participants[] = $p->getParticipant();
        }
        return $participants;
    }

    
//    public function getEventLoadAjax($date, $lng, $lat, $last_id) {
//
//        $em = $this->getEntityManager();
//        $query = $em->createQuery("SELECT event, u "
//                        . "FROM FrontOfficeOptimusBundle:Event event LEFT JOIN event.createur u"
//                        . " where event.active = 1 and event.dateFin > :date and event.lng BETWEEN :lng-0.1 AND :lng+0.1 and event.lat BETWEEN :lat-0.1 AND :lat+0.1"
//                        . " ORDER BY event.dateDebut DESC "
//                )->setParameter('date', $date)
//                ->setParameter('lng', $lng)
//                ->setParameter('lat', $lat)
//                ->setMaxResults(1)
//                ->setFirstResult($last_id);
//
//        return $events = $query->getArrayResult();
//    }
     
}
