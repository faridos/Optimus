<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Competition;
use FrontOffice\OptimusBundle\Entity\ParticipCompetition;
use FrontOffice\OptimusBundle\Entity\PartClubCompetition;
use FrontOffice\OptimusBundle\Form\CompetitionType;
use FrontOffice\OptimusBundle\Form\CompetitionPhotoType;
use FrontOffice\OptimusBundle\Event\ParticipationCompetitionEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use FrontOffice\OptimusBundle\Form\UpdateCompetitionType;
use FrontOffice\OptimusBundle\Entity\Message;
use \DateTime;

/**
 * Competition controller.
 *
 * @Route("/competition")
 */
class CompetitionController extends Controller {

    /**
     * 
     *
     * @Route("/{id}/ajouter", name="add-competition")
     * @Method("GET|POST")
     * @Template()
     */
    public function addAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->findOneBy(array('clubad' => $club, 'member' => $user));
        $competition = new Competition();
        $competition->setClub($club);
        $competition->setActive(true);
        $competition->setDateModification(null);
        $competition->setNbrvu(0);
        $form = $this->createForm(new CompetitionType, $competition);
        $req = $this->get('request');
        if ($req->getMethod() == "POST") {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($competition);
                $em->flush();
                $eventparticipation = new ParticipationCompetitionEvent($member, $competition);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_COMPETITION_REGISTER, $eventparticipation);
                $request->getSession()->getFlashBag()->add('AjoutCompetition', "Compétition  a été creé avec success.");
//                return $this->redirect($this->generateUrl('competition_show', array('id' => $competition->getId())));
                return $this->redirect($this->generateUrl('particip_member_competition', array('id' => $competition->getId())));
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Competition:new.html.twig', array('form' => $form->createView(), 'club' => $club));
    }

    /**
     * 
     *
     * @Route("/{id}/select", name="select-member")
     * @Method("GET|POST")
     * @Template()
     */
    public function selectMemberAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();

        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $club = $competition->getClub();
        return $this->render('FrontOfficeOptimusBundle:Competition:InviterMember.html.twig', array('competition' => $competition, 'club' => $club));
    }

    /**
     * 
     *
     * @Route("/{id}/modifier", name="update-competition")
     * @Method("GET|POST")
     * @Template()
     */
    public function updatecAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        if (!$competition) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $editForm = $this->createForm(new UpdateCompetitionType(), $competition);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $competition->setDateModification(new DateTime());
            $em->flush();
            $request->getSession()->getFlashBag()->add('UpdateCompetition', "Compétition  a été modifier.");
            return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Competition:edit.html.twig', array(
                    'competition' => $competition,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Reward entity.
     *
     * @Route("c/{id}/supprimer", name="delete_competition", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteCompetitionAction($id) {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        if (!$competition) {
            throw $this->createNotFoundException('Unable to find competition entity.');
        }
        $competition->setActive(false);
        $em->merge($competition);
        $em->flush();
        $response = new Response($id);
        return $response;
    }

    /**
     * Deletes a Reward entity.
     *
     * @Route("/{id}/delete", name="delete_competition_profil", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $club = $competition->getClub();
        if (!$competition) {
            throw $this->createNotFoundException('Unable to find competition entity.');
        }
        $competition->setActive(false);
        $em->merge($competition);
        $em->flush();
        $request->getSession()->getFlashBag()->add('SupprissionCompetition', "Compétition  a été supprimer.");
        return $this->redirect($this->generateUrl('show_club', array('id' => $club->getId())));
    }

    /**
     * Finds and displays a Competition entity.
     *
     * @Route("/{id}", name="competition_show")
     * @Method("GET")
     * @Template("FrontOfficeOptimusBundle:Competition:showCempetition.html.twig")
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $entraineurs = $em->getRepository('FrontOfficeUserBundle:User')->findBy(array('profil' => 'Entraineur'));
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $pays = $em->getRepository('FrontOfficeOptimusBundle:Pays')->findAll();
        if (!$competition) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $club = $competition->getClub();
        $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->findOneBy(array('clubad' => $club, 'member' => $user));
        $nbr1 = $competition->getNbrvu();
        $nbr = $nbr1 + 1;
        $competition->setNbrvu($nbr);
        $em->merge($competition);
        $em->flush();
        return array(
            'competition' => $competition,
            'club' => $club,
            'member' => $member,
                'entraineurs' =>  $entraineurs,
            'pays' => $pays
        );
    }

    /**
     * 
     *
     * @Route("/{id}/editphoto", name="setting_competition_photo", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editPhotoCompetitionAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);


        $editForm = $this->createForm(new CompetitionPhotoType(), $competition);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Competition:editPhoto.html.twig', array('competition' => $competition, 'form' => $editForm->createView()));
    }

    /**
     * 
     *
     * @Route("/{id}/participant/{idclub}", name="competition_participant", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function listParticipantAction(Request $request, $id, $idclub) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($idclub);
        $members = $club->getAdherents();
        return $this->render('FrontOfficeOptimusBundle:Competition:listeParticipants.html.twig', array('competition' => $competition, 'members' => $members));
    }

    /**
     * 
     *
     * @Route("/{id}/gerer/{idclub}", name="setting_competition_participant", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editParticipantAction($id, $idclub) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($idclub);
        foreach ($competition->getParticips() as $participCompe) {
            if ($participCompe->getClub() == $club) {
                foreach ($participCompe->getPartclubcomp() as $partisips) {
                    if ($partisips->getParticipant()->getMember() != $club->getCreateur()) {
                        $em->remove($partisips);
                        $em->flush();
                        //return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
                    }
                }
            }
        }

        $request = $this->container->get('request');
        $name = $request->request->get('name');
        if ($name != null) {
            foreach ($name as $member) {
                $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($member);
                $participCompe = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->findOneBy(array('club' => $club, 'competition' => $competition));

                $participation = new PartClubCompetition();
                $participation->setParticips($participCompe);
                $participation->setParticipant($member);
                $em->persist($participation);
                $em->flush();
                return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
            }
        } else {
            $request->getSession()->getFlashBag()->add('SelectionMember', "Il faurt selectionéé des members.");
        }

        return $this->render('FrontOfficeOptimusBundle:Competition:listeParticipants.html.twig', array('competition' => $competition));
    }

    /**
     * 
     *
     * @Route("/{id}/particip/member", name="particip_member_competition", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function participMemberAction($id) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->container->get('request');
        $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
        $club = $competition->getClub();
        $name = $request->request->get('name');
        $participComp = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->findOneBy(array('competition' => $competition, 'club' => $club));
        if (!$participComp) {
            $participation = new ParticipCompetition();
            $participation->setCompetition($competition);
            $participation->setClub($club);
            $participation->setDatePaticipation(new DateTime());
            $em->persist($participation);
            $em->flush();
            if ($name != null) {
                foreach ($name as $member) {
                    $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($member);
                    $part = new PartClubCompetition();
                    $part->setParticips($participation);
                    $part->setParticipant($member);
                    $em->persist($part);
                    $em->flush();
                    return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
                }
            } else {
                $request->getSession()->getFlashBag()->add('SelectionMember', "Il faurt selectionéé des members.");
            }
        } else {
            if ($name != null) {
                foreach ($name as $member) {
                    $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($member);
                    $part = new PartClubCompetition();
                    $part->setParticips($participComp);
                    $part->setParticipant($member);
                    $em->persist($part);
                    $em->flush();
                    return $this->redirect($this->generateUrl('competition_show', array('id' => $id)));
                }
            } else {
                $request->getSession()->getFlashBag()->add('SelectionMember', "Il faurt selectionéé des members.");
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Competition:InviterMember.html.twig', array('competition' => $competition, 'club' => $club));
    }

    /**
     * Deletes a particip entity.
     *
     * @Route("/{id}/particip/delete", name="delete_particip_competition", options={"expose"=true})
     * @Method("GET|DELETE")
     */
    public function deleteParticipAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);
        $particip = $em->getRepository('FrontOfficeOptimusBundle:PartClubCompetition')->findOneBy(array('participant' => $member));

        if (!$particip) {
            throw $this->createNotFoundException('Unable to find competition entity.');
        }

        $em->remove($particip);
        $em->flush();
        $response = new Response($id);
        return $response;
    }
 /**
     * Deletes a particip entity.
     *
     * @Route("/{id}/pays/region", name="regions_pays", options={"expose"=true})
     * @Method("GET")
     */
     public function rechercheRegionAction($id) {
          $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
            $request = $this->get('request');
            $pays = $request->get("pays");
            $comp = $request->get("comp");
            $compteur = $request->get("compteur");
            $compteur = $compteur+1;
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('FrontOfficeUserBundle:User')->getUsers($pays,$user->getId());
       
          $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($comp);
         return $this->render('FrontOfficeOptimusBundle:Competition:regions.html.twig', array('compteur'=>$compteur,'users'=> $users,'competition' => $competition));
    }
    /**
     * Deletes a particip entity.
     *
     * @Route("regions/region", name="regions_region", options={"expose"=true})
     * @Method("GET")
     */
     public function rechercheRegionRegionAction() {
          $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
            $request = $this->get('request');
            $reg = $request->get("reg");
            $comp = $request->get("comp");
            $compteur = $request->get("compteur");
            $compteur = $compteur+1;
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('FrontOfficeUserBundle:User')->getUsersRegions($reg,$user->getId());
         $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($comp);
         return $this->render('FrontOfficeOptimusBundle:Competition:usersregions.html.twig', array('users'=> $users,'competition'=>$competition,'compteur'=>$compteur));
    }
     /**
     * Deletes a particip entity.
     *
     * @Route("{id}/participer/", name="participerCompetition", options={"expose"=true})
     * @Method("GET")
     */
     public function participerCompetitionAction($id) {
         $em = $this->getDoctrine()->getManager();
          $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
          $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
          $clubs = $em->getRepository('FrontOfficeOptimusBundle:Club')->findBy(array('createur' => $user));
         return $this->render('FrontOfficeOptimusBundle:Competition:participerCompetition.html.twig', array('competition' =>$competition, 'clubs'=> $clubs));
    }
     /**
     * 
     *
     * @Route("inviter/entraineur", name="inviter_Entraineur_competition", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
       
     public function inviterCompetitionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
       
     //  $name = Array();
     
            $name = $request->get("name");
            
            $id =  $request->get("comp");
            $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
            $sender = $competition->getClub()->getCreateur();
          
            foreach ($name as $iduder) {
                 $message = new Message();
                $userinvit = $em->getRepository('FrontOfficeUserBundle:User')->find($iduder);
                $content= 'Bonjour '. $userinvit->getNom() .' '. $userinvit->getPrenom() .' je inviter amon Competition '. $competition->getTitre();
              
                $message->setReciever($iduder);
                $message->setSender($sender);
                $message->setMsgTime(new \DateTime());
                $message->setContent($content);
                $message->setCompetition($id);
                $em->persist($message);
                $em->flush();
                  
            }
            return new Response($message); 
        
       
    }
     /**
     * 
     *
     * @Route("adherents/invitation", name="inviter_adherents", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
       
     public function inviterAdherentsAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
       
    
      
     
            $name = $request->get("name");
            $id =  $request->get("idcompetition");
            $idclub =  $request->get("club");
            $competition = $em->getRepository('FrontOfficeOptimusBundle:Competition')->find($id);
             $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($idclub);
                $sender = $club->getCreateur();
                  $message = new Message();
              $participComp = $em->getRepository('FrontOfficeOptimusBundle:ParticipCompetition')->findOneBy(array('competition' => $competition, 'club' => $club));
         $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($sender);
                
            if($participComp != null){
                
                $em->remove($participComp);
                $em->flush();
            }  
            $participation = new ParticipCompetition();
            $participation->setCompetition($competition);
            $participation->setClub($club);
            $participation->setDatePaticipation(new DateTime());
            $em->persist($participation);
            $em->flush();
              $part = new PartClubCompetition();
               $part->setParticips($participation);
                    $part->setParticipant($member);
                    $em->persist($part);
                    $em->flush();
            if ($name != null) {
                foreach ($name as $member) {
                    $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($member);
                    $part = new PartClubCompetition();
                    $part->setParticips($participation);
                    $part->setParticipant($member);
                    $em->persist($part);
                    $em->flush();
                      $userinvit = $em->getRepository('FrontOfficeUserBundle:User')->find($member->getMember()->getId());
                $content= 'Bonjour '. $userinvit->getNom() .' '. $userinvit->getPrenom() .' vous ete participe a la  Competition '. $competition->getTitre();
             
                $message->setReciever($userinvit->getId());
                $message->setSender($sender);
                $message->setMsgTime(new \DateTime());
                $message->setContent($content);
                $message->setCompetition($id);
                $em->persist($message);
                $em->flush(); 
                  
                }
                
              } else {
                $request->getSession()->getFlashBag()->add('SelectionMember', "Il faurt selectionéé des members.");
            }
       
     
    return new Response('rr'); 
        
}

            }
