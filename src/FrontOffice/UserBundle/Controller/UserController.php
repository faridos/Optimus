<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

namespace FrontOffice\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Controller\MessageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontOffice\UserBundle\Entity\User;
use FrontOffice\OptimusBundle\Entity\Palmares;
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\NotificationSeen;
use FrontOffice\UserBundle\Form\UserType;
use FrontOffice\UserBundle\Form\UserPhotoType;
use FrontOffice\UserBundle\Form\UserNameType;
use FrontOffice\UserBundle\Form\UserEmailType;
use Symfony\Component\Validator\Constraints\DateTime;

class UserController extends Controller {

    public function indexAction() {

        return $this->render('FrontOfficeUserBundle:User:redirect.html.twig');
    }

    public function accueilAction() {

        return $this->render('FrontOfficeUserBundle:User:accueil.html.twig');
    }

}
