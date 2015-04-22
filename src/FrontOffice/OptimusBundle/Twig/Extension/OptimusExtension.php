<?php

namespace FrontOffice\OptimusBundle\Twig\Extension;

class OptimusExtension extends \Twig_Extension {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('ParticipantCompetitionOuNon', array($this, 'ParticipantCompetitionOuNon')),
            new \Twig_SimpleFunction('MemberOuNon', array($this, 'MemberOuNon')),
            new \Twig_SimpleFunction('getTypeEvent', array($this, 'getTypeEvent')),
            new \Twig_SimpleFunction('ParticipantOuNon', array($this, 'ParticipantOuNon')),
            new \Twig_SimpleFunction('getNotifications', array($this, 'getNotifications')),
            new \Twig_SimpleFunction('getNombreNotification', array($this, 'getNombreNotification')),
            new \Twig_SimpleFunction('pendingInvitation', array($this, 'pendingInvitation')),
            new \Twig_SimpleFunction('getFriends', array($this, 'getFriends')),
            new \Twig_SimpleFunction('getUser', array($this, 'getUser')),
            new \Twig_SimpleFunction('getUsersInvitations', array($this, 'getUsersInvitations')),
            new \Twig_SimpleFunction('getInvitations', array($this, 'getInvitations')),
            new \Twig_SimpleFunction('getParicipationEventNotification', array($this, 'getParicipationEventNotification')),
            new \Twig_SimpleFunction('getNonSeenMessage', array($this, 'getNonSeenMessage')),
            new \Twig_SimpleFunction('getMembreRequest', array($this, 'getMembreRequest')),
            new \Twig_SimpleFunction('getMembreConfirmed', array($this, 'getMembreConfirmed')),
            new \Twig_SimpleFunction('isParticipant', array($this, 'isParticipant')),
            new \Twig_SimpleFunction('participants', array($this, 'participants')),
            new \Twig_SimpleFunction('isAmi', array($this, 'isAmi')),
            new \Twig_SimpleFunction('adherents', array($this, 'adherents')),
            new \Twig_SimpleFunction('getVote', array($this, 'getVote')),
            new \Twig_SimpleFunction('getRechercheUsers', array($this, 'getRechercheUsers')),
            new \Twig_SimpleFunction('getRechercheEvents', array($this, 'getRechercheEvents')),
            new \Twig_SimpleFunction('getRechercheClubs', array($this, 'getRechercheClubs')),
        );
    }
    public function  getRechercheUsers(){
        return $this->em->getRepository('FrontOfficeUserBundle:User')->findAll();
    }
    public function  getRechercheEvents(){
        return $this->em->getRepository('FrontOfficeOptimusBundle:Event')->findBy(array('active'=> 1));
    }
    public function  getRechercheClubs(){
        return $this->em->getRepository('FrontOfficeOptimusBundle:Club')->findBy(array('active'=> 1, 'isPayant'=> 1));
    }
     public function  memberclub($id){
       $club = $this->em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        return $this->em->getRepository('FrontOfficeOptimusBundle:Member')->findBy(array('clubad'=> $club));
    }
    public function  adherents($id){
      return $this->em->getRepository('FrontOfficeOptimusBundle:Member')->getMembers($id);
    }
    public function  participants($event){
         return $this->em->getRepository("FrontOfficeOptimusBundle:Event")->getParticipants2($event, $event->getCreateur());
    }
           
    public function isParticipant($event,$user)
    {
         $participants = $this->em->getRepository("FrontOfficeOptimusBundle:Event")->getParticipants($event);
        $isparticipant= false;
        foreach($participants as $participateur){
        if($participateur == $user){
            $isparticipant = true;
        }  
        }
        return  $isparticipant;
    }
    
    public function isAmi($id,$userami)
    {
        $amis = $this->em->getRepository("FrontOfficeUserBundle:User")->getFrinds($id);
        $isami= false;
        foreach($amis as $ami){
        if($ami == $userami){
            $isami = true;
        }  
        }
        return  $isami;
    }


    public function getTypeEvent() {
        return $this->em->getRepository('FrontOfficeOptimusBundle:TypeEvent')->findAll();
    }
    
    public function getMembreRequest($club,$user) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Member')->getMembersRequest($club,$user);
    }
     public function MemberOuNon($club, $user) {
        return $this->em->getRepository("FrontOfficeOptimusBundle:Club")->MemberOuNon($club, $user);
    }

    public function getMembreConfirmed($user, $club) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Member')->findOneBy(array('member' => $user, 'clubad' => $club));
         
    }

    public function ParticipantOuNon($event, $user) {
        return $this->em->getRepository("FrontOfficeOptimusBundle:Event")->ParticipantOuNon($event, $user);
    }
     public function ParticipantCompetitionOuNon($competition, $member) {
        return $this->em->getRepository("FrontOfficeOptimusBundle:Competition")->ParticipantCompetitionOuNon($competition, $member);
    }

    public function getNonSeenMessage($id) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Message')->NonseenMsg($id);
    }

    public function getUsersInvitations($id) {
        return $this->em->getRepository('FrontOfficeUserBundle:User')->find($id);
    }

    public function getInvitations($id) {

        return $this->em->getRepository('FrontOfficeUserBundle:User')->getInvitations($id);
    }

    public function getParicipationEventNotification($id,$id_user, $date ) {
        return $this->em->getRepository('FrontOfficeOptimusBundle:Participation')->getParicipationEventNotification($id,$id_user, $date);
    }

    public function getUser($id) {
        return $notificateur = $this->em->getRepository('FrontOfficeUserBundle:User')->find($id);
    }

    public function getFriends($id) {
        $Friends = $this->em->getRepository('FrontOfficeUserBundle:User')->getFrinds($id);
//        $entities2 = $this->em->getRepository('FrontOfficeUserBundle:User')->getLeftFrinds($id);
//        $Friends = array_merge($entities1, $entities2);
        return $Friends;
    }

    public function pendingInvitation($user, $user1) {
        $pendingInvitations = $this->em->getRepository('FrontOfficeUserBundle:User')->getpendingInvitations($user->getId(), $user1->getId());
        return $pendingInvitations;
    }
    
    public function getVote($rating,$voter) {

        $vote = $this->em->getRepository('FrontOfficeRatingBundle:Vote')->findOneBy(array("rating"=>$rating,"voter"=>$voter));
        return $vote;
    }

    public function getNotifications($id) {

        $notifications = $this->em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findBy(array("users"=>$id),array("datenotificationseen"=>'DESC'));
        return $notifications;
    }

    public function getNombreNotification($id) {
                $notifications = $this->em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findBy(array("users"=>$id),array("datenotificationseen"=>'DESC'));
                $nombre=0;
                foreach ($notifications as $notif){
                    if($notif->getVu() == "0"){
                    $nombre++;
                }
                }
        
        return $nombre;
    }

    public function getName() {
        return 'optimus_extension';
    }

}
