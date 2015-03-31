<?php

namespace FrontOffice\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserAmisType  extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
                  ->add('amis', 'integer', array('label' => 'amis','attr'=>array('class'=>'search-optimus','style'=>'margin-top: 0px')));
                 
                  
     }
      /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    

    public function getName() {
         return 'frontoffice_userbundle_user';
    }

    //put your code here
}
