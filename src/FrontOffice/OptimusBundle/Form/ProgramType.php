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
            ->add('nom','text',array('attr'=>array('class'=>'form-control')))
            ->add('description','text',array('attr'=>array('class'=>'form-control')))
            ->add('datedebut','date', array('years' => range( date('Y'),1930), 'format' => 'dd-MMMM-yyyy'))
            ->add('datefin','date', array('years' => range( date('Y'),1930), 'format' => 'dd-MMMM-yyyy'));
         
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
