<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ClubRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends EntityRepository {

//    public function postComments($param) {
//        $qb = $this->createQueryBuilder('c')
//                ->select('c')
//                ->where('c.id = :param')
//                ->setParameter('id', $param)
//                ->addSelect('c');
//        return $qb->getQuery()
//                        ->getResult();
//    }
//
//    public function CommentsLoad($idc, $last_id) {
//
//        $em = $this->getEntityManager();
//        $query = $em->createQuery("SELECT comment, u "
//                        . "FROM FrontOfficeOptimusBundle:Comment comment LEFT JOIN comment.commenteur u"
//                        . " where comment.club = :id"
//                        . " ORDER BY comment.createdat DESC"
//                )->setParameter('id', $idc)
//                ->setMaxResults(5)
//                ->setFirstResult($last_id);
//        return $clubs = $query->getArrayResult();
//    }
//
    public function CommentsEventLoad($ide, $last_id) {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT comment, u "
                        . "FROM FrontOfficeOptimusBundle:Comment comment LEFT JOIN comment.commenteur u"
                        . " where comment.event = :id"
                        . " ORDER BY comment.createdat DESC"
                )->setParameter('id', $ide)
                ->setMaxResults(5)
                ->setFirstResult($last_id);
        return $events = $query->getArrayResult();
    }

}
