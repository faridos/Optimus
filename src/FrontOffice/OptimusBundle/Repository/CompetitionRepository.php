<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CompetitionRepository extends EntityRepository {
    public function ParticipantCompetitionOuNon($competition, $member) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $participant = $qb->select('p')
                        ->from("FrontOfficeOptimusBundle:ParticipCompetition", 'p')
                        ->where("p.competition = :competition")
                        ->andWhere("p.participant= :member")
                        ->setParameters(array('competition' => $competition, 'member' => $member))
                        ->getQuery()->getResult();

        return count($participant); // retourne 0 ou 1
    }
}
