<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CompetitionRepository extends EntityRepository {
    public function ParticipantCompetitionOuNon($competition, $member) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $participant = $qb->select('pc')
                        ->from("FrontOfficeOptimusBundle:PartClubCompetition", 'pc')
                        ->Join("FrontOfficeOptimusBundle:ParticipCompetition", 'p', "WITH", 'pc.particips = p.id')
                        ->where("p.competition = :competition")
                        ->andWhere("p.participant= :member")
                        ->setParameters(array('competition' => $competition, 'member' => $member))
                        ->getQuery()->getResult();

        return count($participant); // retourne 0 ou 1
    }
    
    public function getCompetitionJour() {

        $em = $this->getEntityManager();
        $dt = new \Datetime();
        $dt = $dt->modify('+ 1 days');
        $dt2 = new \DateTime();
        $dt2 = $dt2->modify('+ 2 days');
        $query = $em->createQuery("SELECT competition "
                        . "FROM FrontOfficeOptimusBundle:Competition competition"
                        . " where competition.active = 1 and competition.dateDebut BETWEEN  '" . $dt->format("Y-m-d") . " 00:00:00' and '" . $dt2->format("Y-m-d") . " 23:59:59'"
                );

        return $competitions = $query->getResult();
    }
}
