<?php

namespace FrontOffice\OptimusBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ClubRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClubRepository extends EntityRepository {
   
    public function getClubsMember($id) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder('c');
        $qb->select('c')
                ->from('FrontOffice\OptimusBundle\Entity\Club', 'c')
                ->innerJoin('FrontOffice\OptimusBundle\Entity\Member', 'm')
                ->where('m.clubad = c.id')
                ->andWhere('c.active = 1')
                ->andWhere('m.member = :id')
                ->andWhere('m.confirmed = 1')
                ->setParameter('id', $id);
        return $qb->getQuery()->getResult();
    }
     
    public function getClubs($nomClub, $sport, $adresse) {
        $em = $this->getEntityManager();
        $clubs=Null;
        
        $condition = "WHERE 1=1";
        $condition = $condition . " AND c.active='1' ";
        $parameters = array();

        if ($sport != null) {
            $condition = $condition . " AND UPPER(c.discpline) LIKE :sport";
            $parameters[':sport'] = strtoupper($sport) . "%";
        }

        if ($adresse != null) {
            $condition = $condition . " AND UPPER(c.adresse) LIKE :adr";
            $parameters[':adr'] = "%". strtoupper($adresse) . "%";
        }
        if ($nomClub != null) {
        $condition = $condition . " AND UPPER(c.nom) LIKE :nomClub";
        $parameters[':nomClub'] = "%". strtoupper($nomClub) . "%";
        }
        if($sport || $adresse || $nomClub != Null){
        $clubs = $em->createQuery("SELECT c FROM FrontOfficeOptimusBundle:Club c " . $condition)
                        ->setParameters($parameters)->getResult();
        }
        return $clubs;
    }
    
    public function getClubCreateurAm($createur, $nomClub, $sport, $adresse, $usercrt) {
       $em = $this->getEntityManager(); 
        // $em = $this->getDoctrine()->getEntityManager();
         //$usercrt = $this->container->get('security.context')->getToken()->getUser();            
         $friends = $em->getRepository("FrontOfficeUserBundle:User")->getFrinds($usercrt->getId());
        $res = null;

        foreach ($friends  as $friend)  {
            $condition = "WHERE 1=1";
            $condition = $condition . " AND c.active='1' ";
            $parameters = array();


            if ($friend != null) {
                $condition = $condition . " AND c.createur in (:friend)";
                $parameters[':friend'] = $friend;
            }

            if ($sport != null) {
                $condition = $condition . " AND UPPER(c.discpline) LIKE :sport";
                $parameters[':sport'] = strtoupper($sport) . "%";
            }

            if ($adresse != null) {
                $condition = $condition . " AND UPPER(c.adresse) LIKE :adr";
                $parameters[':adr'] = "%". strtoupper($adresse) . "%";
            }

            $condition = $condition . " AND UPPER(c.nom) LIKE :nom";
            $parameters[':nom'] = "%". strtoupper($nomClub) . "%";




            $res[] = $em->createQuery("SELECT c FROM FrontOfficeOptimusBundle:Club c " . $condition)
                            ->setParameters($parameters)->getResult();
        }
        return $res; 
    }
    
    public function getClubsCreateur($createur, $nomClub, $sport, $adresse) {
        $em = $this->getEntityManager();
        $users = $em->getRepository("FrontOfficeUserBundle:User")->getUsersByName($createur);

        $res = null;

        foreach ($users as $user) {
            $condition = "WHERE 1=1";
            $condition = $condition . " AND c.active='1' ";
            $parameters = array();

            if ($user != null) {
                $condition = $condition . " AND c.createur = :createur";
                $parameters[':createur'] = $user->getId();
            }

            if ($sport != null) {
                $condition = $condition . " AND UPPER(c.discpline) LIKE :sport";
                $parameters[':sport'] = strtoupper($sport) . "%";
            }

            if ($adresse != null) {
                $condition = $condition . " AND UPPER(c.adresse) LIKE :adr";
                $parameters[':adr'] = "%". strtoupper($adresse) . "%";
            }

            $condition = $condition . " AND UPPER(c.nom) LIKE :nom";
            $parameters[':nom'] = "%". strtoupper($nomClub) . "%";




            $res[] = $em->createQuery("SELECT c FROM FrontOfficeOptimusBundle:Club c " . $condition)
                            ->setParameters($parameters)->getResult();
        }
        return $res;
    }
    
    public function getClubsSearch($nom) {
      $qb=$this->getEntityManager()->createQueryBuilder();
      $clubs=$qb->select('c')
                     ->from("FrontOfficeOptimusBundle:Club", 'c')
                     ->where("UPPER(c.nom) LIKE :key")
                     ->setParameter('key', '%'.strtoupper($nom).'%')
                     ->getQuery()->getResult();
     return $clubs;
    }

}
