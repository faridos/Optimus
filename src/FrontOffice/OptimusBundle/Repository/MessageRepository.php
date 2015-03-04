<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ClubRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository {

    public function NonseenMsg($id) {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT m, u FROM FrontOfficeOptimusBundle:Message m"
                        . " LEFT JOIN m.reciever u where m.is_seen = 0 and u.id = :id"
                . " ORDER BY m.msgTime DESC " 
                )
                ->setParameter('id', $id);
        return $events = $query->getResult();
    }

//
//    public function findMsgDestinataires($idprofil) {
//        $em = $this->getEntityManager();
//
//        $qb = $em->createQueryBuilder();
//
//        $qb->select('m')
//                ->from('FrontOffice\OptimusBundle\Entity\Message', 'm')
//                ->where('m.reciever != :idprofil')
//                ->setParameter('idprofil', $idprofil);
//
//        return $qb->getQuery()->getResult();
//    }
//
//    public function findConversation($id_user) {
//        $em = $this->getEntityManager();
//
//        $qb = $em->createQueryBuilder();
//
//        $qb->select('m')
//                ->from('FrontOffice\OptimusBundle\Entity\Conversation', 'm')
//                ->where('m.user1 = :id_user' or 'm.user2 = :id_user')
//                ->setParameter('id_user', $id_user);
//
//        return $qb->getQuery()->getResult();
//    }
//
//    public function MsgConversation($idconvers) {
//
//        $em = $this->getEntityManager();
//        $query = $em->createQuery("SELECT msg,u1, u2  "
//                        . "FROM FrontOfficeOptimusBundle:Message msg LEFT JOIN msg.sender u1 LEFT JOIN msg.reciever u2 "
//                        . " where msg.conversationroom = :id "
//                )->setParameter('id', $idconvers);
//        return $messages = $query->getArrayResult();
//    }
//
//    public function getLastConversationMessages($idconvers) {
//        $em = $this->getEntityManager();
//        return $messages = $em->createQuery("SELECT msg, c, u1, u2 FROM FrontOfficeOptimusBundle:Message msg "
//                        . " LEFT JOIN msg.conversationroom c "
//                        . " LEFT JOIN msg.sender u1 "
//                        . " LEFT JOIN msg.reciever u2 "
//                        . "WHERE c.id = :id"
//                )->setParameter('id', $idconvers)
//                ->getResult();
//    }
}
