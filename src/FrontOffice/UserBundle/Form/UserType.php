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
               
                 ->add('type_notification','choice', array('choices' => array('All'=>'voir toutes les notifications',
                     'E' => 'voir les notifications évènements',
                     'C' => 'voir les notifications clubs',
                     'U' => 'voir les notifications inscription entraineur',
                     'EC' => 'voir les notifications évènements et clubs',
                     'EU' => 'voir les notifications évènements inscription entraineur',
                     'UC' => 'voir les notifications clubs inscription entraineur',
                     'NOT'=>' ne voir aucune notifications '),'attr'=> array('class'=>'search-optimus')));
         
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
