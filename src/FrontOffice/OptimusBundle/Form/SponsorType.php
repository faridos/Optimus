<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SponsorType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('file','file',array('attr'=>array('class'=>'file','multiple data-show-upload'=>'false','data-show-caption'=>'false', 'data-show-remove'=>'false')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Sponsor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_sponsor';
    }
}
