<?php

namespace FrontOffice\PaymentBundle\Controller;

use Payum\Bundle\PayumBundle\Controller\PayumController;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\OrderInterface;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Sync;
use FrontOffice\OptimusBundle\Entity\Club;
use FrontOffice\OptimusBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\Request;

class DetailsController extends PayumController {

    public function viewAction(Request $request) {
        $token = $this->getHttpRequestVerifier()->verify($request);

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        try {
            $payment->execute(new Sync($token));
        } catch (RequestNotSupportedException $e) {
            
        }

        $payment->execute($status = new GetHumanStatus($token));

        $refundToken = null;
        if ($status->isCaptured() || $status->isAuthorized()) {
            $refundToken = $this->getTokenFactory()->createRefundToken(
                    $token->getPaymentName(), $status->getModel(), $request->getUri()
            );
        }

        return $this->render('FrontOfficePaymentBundle:Details:view.html.twig', array(
                    'status' => $status->getValue(),
                    'details' => htmlspecialchars(json_encode(
                                    iterator_to_array($status->getModel()), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                    )),
                    'paymentTitle' => ucwords(str_replace(array('_', '-'), ' ', $token->getPaymentName())),
                    'refundToken' => $refundToken
        ));
    }

    public function viewOrderAction(Request $request) {
        $token = $this->getHttpRequestVerifier()->verify($request);

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        try {
            $payment->execute(new Sync($token));
        } catch (RequestNotSupportedException $e) {
            
        }

        $payment->execute($status = new GetHumanStatus($token));

        /** @var OrderInterface $order */
        $order = $status->getFirstModel();
        $idclub = $this->get('session')->get('idclub');
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($status->getValue() == 'captured') {
            $club = $em->getRepository("FrontOfficeOptimusBundle:Club")->find($idclub);
            $club->setIsPayant(1);
            $em->merge($club);
            $em->flush();
           
            $notif = new Notification();
            $notif->setNotificateur($user);
            $notif->setType('addClub');
            $notif->setClub($club);
            $notif->setEntraineur($user);
            $em->persist($notif);
            $em->flush();
           $request->getSession()->getFlashBag()->add('ActivationClub', " Payment Complete : Votre Club  est Maintenant Activé ."); 
        }
        
        else{
         $request->getSession()->getFlashBag()->add('EchecActivationClub', "Echéc de payment.");
        }
        return $this->redirect($this->generateUrl('show_club', array('id' => $idclub)));
    }

}
