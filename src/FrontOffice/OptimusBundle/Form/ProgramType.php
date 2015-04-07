<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\ExecutionContextInterface;
class ProgramType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'nom du programme')))
          //  ->add('description','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'description du programme')))
            ->add('datedebut', 'datetime' ,array('attr'=>array('class'=>'some_class search-optimus','placeholder'=>'date debut'),'widget' => 'single_text','required' => true))
            ->add('datefin', 'datetime' ,array('attr'=>array('class'=>'some_class search-optimus','placeholder'=>'date fin'),'widget' => 'single_text','required' => true));
         
        }   
           
        
    
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
       $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Program'
        ));
      
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_program';
    }
    
    
}
