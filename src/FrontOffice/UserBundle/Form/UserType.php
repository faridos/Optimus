<?php

namespace FrontOffice\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UserType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
               
                 ->add('type_notification','choice', array('choices' => array('All'=>'Tout','NOT'=>'Aucune','EC' => 'Evenement et Club','EU' => 'Evenement et nouvelle inscription',
                            'UC' => 'User et club','E' => 'event','C' => 'club','U' => 'user'),'attr'=>array('class'=>'form-control')));
         
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
