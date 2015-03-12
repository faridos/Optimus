<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('nom','text',array('attr'=>array('class'=>'form-control','placeholder'=>'Entrer le titre album')))
             ->add('privacy', 'choice', array('choices' => array('Publique' => 'Public', 'Unique' => 'Unique', 'Amis' => 'Amis'),'attr'=>array('class'=>'form-control')))
        ;
    }               
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Album'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_album';
    }
}
