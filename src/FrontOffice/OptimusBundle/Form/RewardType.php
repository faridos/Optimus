<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RewardType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'datetime' ,array('attr'=>array('class'=>'some_class search-optimus','placeholder'=>'Date de creation'),'widget' => 'single_text','required' => true))
            ->add('titre','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'Entrer le titre de reward')))
            ->add('classment', 'text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'Entrer le classement')))
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Reward'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_reward';
    }
}
