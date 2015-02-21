<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre','text',array('attr'=>array('class'=>'form-control')))
            ->add('lieu','text',array('attr'=>array('class'=>'form-control'),'required'=>True))
            ->add('dateDebut','datetime', array('data' => new \DateTime('now'), 'format' => 'dd-MMMM-yyyy'))
            ->add('dateFin','datetime', array('data' => new \DateTime("now"), 'format' => 'dd-MMMM-yyyy'))
            ->add('nbrPlaces','integer',array('attr'=>array('min'=>0,'class'=>'form-control')))
            ->add('frais','number',array('attr'=>array('class'=>'form-control')))
            ->add('url','url',array('attr'=>array('class'=>'form-control'),'required'=>false))
            ->add('description','textarea',array('attr'=>array('class'=>'form-control'),'required'=>false))
            ->add('type','entity', array(  'attr'=>array('class'=>'form-control'),
                                           'class' => 'FrontOfficeOptimusBundle:TypeEvent',
                                           'property'=>'nom'))
            ->add('lat','number',array('attr'=>array('class'=>'form-control')),array('required'=>true))
            ->add('lng','number',array('attr'=>array('class'=>'form-control')),array('required'=>true))
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_event';
    }
}
