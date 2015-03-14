<?php

namespace FrontOffice\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UserPhotoType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
                   ->add('file', 'file' ,array('attr'=>array('class'=>'form-control'),'required'=>True));
         
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
