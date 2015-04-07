<?php

namespace FrontOffice\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FrontOfficePaymentBundle:Default:index.html.twig', array('name' => $name));
    }
}
