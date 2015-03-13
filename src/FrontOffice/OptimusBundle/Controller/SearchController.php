<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Event controller.
 *
 * @Route("/Recherche")
 */
class SearchController extends Controller {

// Recherche personne
    /**
     * 
     *
     * @Route("/users", name="search_user", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function searchUsersAction() {
        $request = $this->getRequest();
        $nom = $request->query->get('key');
        $tel = $request->query->get('tel');
        $adresse = $request->query->get('adresse');
        $profil = $request->query->get('profil');
        $email = $request->query->get('email');
        $usersValide = Null;
        
        $condition = "WHERE 1=1";
        $parameters = array();

        if ($tel != null) {
            $condition = $condition . " AND u.tel=:tel";
            $parameters[':tel'] = $tel;
        }
        if ($adresse != null) {
            $condition = $condition . " AND UPPER(u.adresse) LIKE :adr";
            $parameters[':adr'] = "%" . strtoupper($adresse) . "%";
        }
        if ($profil != null) {
            $condition = $condition . " AND UPPER(u.profil) LIKE :profil";
            $parameters[':profil'] = strtoupper($profil) . "%";
        }
        if ($email != null) {
            $condition = $condition . " AND u.email = :email";
            $parameters[':email'] = $email;
        }
        if ($nom != null) {
        $condition = $condition . " AND (UPPER(u.nom) LIKE :nom OR UPPER(u.prenom) LIKE :nom) ";
        $parameters[':nom'] = strtoupper($nom) . "%";
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        if($nom || $email || $profil || $adresse || $tel != Null){
           $users = $em->createQuery("SELECT u FROM FrontOfficeUserBundle:user u " . $condition);
            $users->setParameters($parameters);
            $usersValide = $users->getResult();
        }
            
       
        $res['users'] = $usersValide;
        $res['key'] = $nom;
        $res['tel'] = $tel;
        $res['adresse'] = $adresse;
        $res['profil'] = $profil;
        $res['email'] = $email;
        $res['nbr_users'] = count($res['users']);

        $user = $this->container->get('security.context')->getToken()->getUser();
        
        return $this->render("FrontOfficeOptimusBundle:Search:searchUsers.html.twig", array('res' => $res, 'user' => $user));
    }
//_____________________________________________________________________________________________________
//Recherche Event
    /**
     * 
     * @Route("/evenements", name="search_events", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function searchEventsAction() {

        $request = $this->getRequest();
        $titre = $request->query->get('key');
        $type = $request->query->get('type');
        $lieu = $request->query->get('lieu');
        $date = $request->query->get('date');
        $createur = $request->query->get('createur');
        $events = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        if ($createur == 'tous') {
            $resultats = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEvents($titre, $type, $lieu, $date);
            if ($resultats != null) {
                $events=$resultats;
           }
        } elseif ($createur == 'amis') {
            $usercrt = $this->container->get('security.context')->getToken()->getUser();
              $resultats = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsCreateurAm($createur, $titre, $type, $lieu, $date, $usercrt);
            if ($resultats != null) {
                foreach ($resultats as $resultat) {
                    foreach ($resultat as $ev) {
                        $events[] = $ev;
                    }
                }
            }
       
        } elseif ($createur != null) {
            $resultats = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsCreateur($createur, $titre, $type, $lieu, $date);
            if ($resultats != null) {
                foreach ($resultats as $resultat) {
                    foreach ($resultat as $ev) {
                        $events[] = $ev;
                    }
                }
            } else
            // $events = $em->createQuery("SELECT e FROM FrontOfficeOptimusBundle:Event e WHERE e.createur='' " );
                $events = NULL;
            // die ("inexistant")
            ;
        }


        else {
            $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEvents($titre, $type, $lieu, $date);
        }
        

        $res['events'] = $events;
        $res['key'] = $titre;
        $res['type'] = $type;
        $res['lieu'] = $lieu;
        $res['createur'] = $createur;
        $res['date'] = $date;
        $res['nbr_events'] = count($res['events']);


        return $this->render("FrontOfficeOptimusBundle:Search:searchEvents.html.twig", array('res' => $res));
    }
//_____________________________________________________________________________________________________
    //Recherche Club
    /**
     * 
     *
     * @Route("/clubs", name="search_clubs", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function searchClubsAction() {

        $request = $this->getRequest();
        $nomClub = $request->query->get('key');
        $sport = $request->query->get('sport');
        $adresse = $request->query->get('adresse');
        $createur = $request->query->get('createur');
        $clubs = NULL;
        $em = $this->getDoctrine()->getEntityManager();
        
        if ($createur == 'tous') {
           $resultats  = $em->getRepository("FrontOfficeOptimusBundle:Club")->getClubs($nomClub, $sport, $adresse);
             if ($resultats != null) {
                $clubs = $resultats;
        }
        
             }
       elseif ($createur == 'amis') {
           $usercrt = $this->container->get('security.context')->getToken()->getUser(); 
       $resultats = $em->getRepository("FrontOfficeOptimusBundle:Club")->getClubCreateurAm($createur, $nomClub, $sport, $adresse, $usercrt);
       if ($resultats != null) {
                //$clubs = $resultats;
           foreach ($resultats as $resultat) {
                    foreach ($resultat as $cl) {
                        $clubs[] = $cl;
                    }
                }
           } 
           else
                $clubs = NULL;
       }
       
       
      elseif ($createur != null) {
            $resultats = $em->getRepository("FrontOfficeOptimusBundle:Club")->getClubsCreateur($createur, $nomClub, $sport, $adresse);
            if ($resultats != null) {
                foreach ($resultats as $resultat) {
                    foreach ($resultat as $cl) {
                        $clubs[] = $cl;
                    }
                }
            } else
                $clubs = NULL;
            // die ("inexistant")
            
        }

        else {
            $clubs = $em->getRepository("FrontOfficeOptimusBundle:Club")->getClubs($nomClub, $sport, $adresse);

        }
        $res['clubs'] = $clubs; //->getResult();
        $res['key'] = $nomClub;
        $res['sport'] = $sport;
        $res['adresse'] = $adresse;
        $res['createur'] = $createur;
        $res['nbr_clubs'] = count($res['clubs']);

        //parametres envoyer au layout.html.tiwg
        $user = $this->container->get('security.context')->getToken()->getUser();
        


        return $this->render("FrontOfficeOptimusBundle:Search:searchClubs.html.twig", array('res' => $res, 'user' => $user));
    }

    /**
     * 
     *
     * @Route("/index", name="search_index", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function indexAction() {
        $request = $this->getRequest();
        $key = $request->query->get('key');
        $users = Null;
        $clubs = Null;
        $events = Null;
        $em = $this->getDoctrine()->getEntityManager();

        if ($key != null) {

        $users = $em->getRepository("FrontOfficeUserBundle:User")->getUsersByName($key);
        $clubs= $em->getRepository("FrontOfficeOptimusBundle:Club")->getClubsSearch($key);
        $events= $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsSearch($key);
        
        }
        
        
            
       
        $res['users'] = $users;
        $res['clubs'] = $clubs;
        $res['events'] = $events;
        $res['nbr_users'] = count($res['users']);

        $user = $this->container->get('security.context')->getToken()->getUser();
        
        return $this->render("FrontOfficeOptimusBundle:Search:index.html.twig", array('res' => $res, 'user' => $user));
    }
}

