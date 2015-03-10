<?php

namespace FrontOffice\OptimusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClubType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom','text',array('attr'=>array('class'=>'search-optimus','placeholder'=>'Entrer le nom du club')))
            ->add('file', 'file' ,array('attr'=>array('class'=>'form-control')))
            ->add('dateCreation','datetime', array('years' => range( date('Y'),1930), 'format' => 'dd-MMMM-yyyy'))
            ->add('discpline','text',array('attr'=>array('class'=>'form-control')))
                  ->add('description','text',array('attr'=>array('class'=>'form-control')))
            ->add('adresse','text',array('attr'=>array('class'=>'form-control'),'required'=>True))
           
            ->add('fraisAdhesion','text',array('attr'=>array('class'=>'form-control')))
            ->add('lat','number',array('attr'=>array('class'=>'form-control', )),array('required'=>true))
            ->add('lng','number',array('attr'=>array('class'=>'form-control',)),array('required'=>true))
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontOffice\OptimusBundle\Entity\Club'
           
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'frontoffice_optimusbundle_club';
    }
}
