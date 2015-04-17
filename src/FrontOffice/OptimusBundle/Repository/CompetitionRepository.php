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
}
