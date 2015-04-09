<?php

namespace FrontOffice\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FrontOffice\PaymentBundle\Model\PaymentDetails as BasePaymentDetails;

/**
 * @ORM\Table(name="payum_payment_details")
 * @ORM\Entity
 */
class PaymentDetails  extends BasePaymentDetails {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}