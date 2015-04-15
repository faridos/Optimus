<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateCompetitionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'Entrer le Type (Obligatoire)')))
        
            ->add('titre','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'Entrer le titre (Obligatoire)')))
            ->add('lieu','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'Entrer le lieu' , 'readonly'=>'readonly'),'required'=>True))
            ->add('dateDebut','datetime', array('attr'=>array('class'=>'some_class search-optimus','placeholder'=>'Entrer la date de début (Obligatoire)'),'widget' => 'single_text','required' => true))
            ->add('dateFin','datetime', array('attr'=>array('class'=>'some_class search-optimus','placeholder'=>'Entrer la date de fin (Obligatoire)'),'widget' => 'single_text','required' => true))
            ->add('nbrPlaces','integer',array('attr'=>array('min'=>0,'class'=>'search-optimus','placeholder'=>'Entrer le nombre de places'),'required'=>False))
            ->add('frais','integer',array('attr'=>array('min'=>0,'class'=>'form-control round sansBorder','placeholder'=>'Entrer les frais d\'inscription'),'required'=>False))
            ->add('description','textarea',array('attr'=>array('class'=>'form-control search-optimus','placeholder'=>'Entrer la description du votre compétition...'),'required'=>false))
            ->add('lat','number',array('attr'=>array('class'=>'search-optimus')),array('required'=>true))
            ->add('lng','number',array('attr'=>array('class'=>'search-optimus')),array('required'=>true))
          
          
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Competition'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_competition';
    }
}
