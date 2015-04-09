<?php

namespace FrontOffice\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * @ORM\Table(name="paymenttoken")
 * @ORM\Entity
 */
class PaymentToken extends Token {
    
}