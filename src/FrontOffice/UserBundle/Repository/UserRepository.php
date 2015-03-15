<?php

namespace FrontOffice\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository {

    public function getFrinds($id) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('u');
        $qb->select('u')
                ->from('FrontOffice\UserBundle\Entity\User', 'u')
                ->Join('Sly\RelationBundle\Entity\Relation', 'r')
                ->where('r.object2Id = u.id and r.object1Id = :id')
                ->orWhere('r.object1Id = u.id and r.object2Id = :id')
                ->andWhere('r.confirmed = 1')
                ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }

   

    public function getInvitations($id) {
        // Retourne les invitations
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select(array('r'))
           ->addSelect('u.username','u.nom','u.prenom', 'u.path', 'r.object1Id','r.id','r.createdAt')
           ->from('Sly\RelationBundle\Entity\Relation', 'r')
          ->Join('FrontOffice\UserBundle\Entity\User', 'u')
           ->where('r.confirmed = 0 and r.object1Id = u.id and r.object2Id = :id ')                
         
           ->setParameter('id', $id);
        return $qb->getQuery() ->getArrayResult();
    }

    public function getpendingInvitations($idother, $idcurrent) {
        // Retourne les invitations
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('r.name', 'r.object1Id', 'r.object2Id', 'r.confirmed')
                ->from('Sly\RelationBundle\Entity\Relation', 'r')
                ->where('r.confirmed = 0 and r.object1Id = :id and r.object2Id = :idc' )
                ->setParameter('id', $idother)
                ->setParameter('idc', $idcurrent);
        return $qb->getQuery()->getArrayResult();
    }

    public function getUsers() {
//     $em = $this->getEntityManager();
//     $users=$em->getRepository('FrontOffice\UserBundle\Entity\User')->findAll();
//     return $users;
    }

    // added by farido tornado; 2 methods retournants l'user courant et son lastTactivity
    public function getCurrentUser($id) {
//     $em = $this->getEntityManager();
//     $currentuser=$em->getRepository('FrontOffice\UserBundle\Entity\User')->find($id);
//     return $currentuser;
    }

    public function getActive() {
        // Comme vous le voyez, le délais est redondant ici, l'idéale serait de le rendre configurable via votre bundle
//        $delay = new \DateTime();
//        $delay->setTimestamp(strtotime('2 minutes ago'));
// 
//        $qb = $this->createQueryBuilder('u')
//            ->where('u.lastActivity > :delay')
//            ->setParameter('delay', $delay)
//        ;
// 
//        return $qb->getQuery()->getResult();
    }

    public function getUsersByName($nomOuPrenom) {
      $qb=$this->getEntityManager()->createQueryBuilder();
      $user=$qb->select('u')
                     ->from("FrontOfficeUserBundle:User", 'u')
                     ->where("UPPER(u.nom) LIKE :nomPrenom")
                     ->orWhere("UPPER(u.prenom) LIKE :nomPrenom")
                     ->setParameter('nomPrenom', strtoupper($nomOuPrenom).'%')
                     ->getQuery()->getResult();
     return $user;
    }

    public function getConnectedUsers() {
//        $dateCourante= new \Datetime();
//      $qb=$this->getEntityManager()->createQueryBuilder();
//      $user=$qb->select('u')
//                     ->from("FrontOfficeUserBundle:User", 'u')
//                     
//                     ->having("u.lastLogin < :datecourante")
//                     ->setParameter('datecourante', $dateCourante)
//                     ->getQuery()->getResult();
//     return $user;
    }

}
