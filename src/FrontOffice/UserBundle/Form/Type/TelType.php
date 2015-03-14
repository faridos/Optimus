<?php

namespace FrontOffice\UserBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
class TelType extends AbstractType
{
    /**
     * @return  string
     */
    public function getName()
    {
        return 'tel';
    }
 
    /**
     * @return  string
     */
    public function getParent()
    {
        return 'text';
    }
}