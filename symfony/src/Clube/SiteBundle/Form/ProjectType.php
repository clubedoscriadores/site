<?php

namespace Clube\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Nome'))
            ->add('objective', null, array('label' => 'Objetivos'))
            ->add('detail', null, array('label' => 'Detalhes'))
            ->add('requirements', null, array('label' => 'Requisitos'))
            ->add('maxIdeas', 'integer', array('label' => 'Máximo de ideias'))
            ->add('maxVideos', 'integer', array('label' => 'Máximo de videos'))
            ->add('requirements', null, array('label' => 'Requisitos'))
            ->add('ideaEndDate', 'date', array('label' => 'Limite ideia','format' => 'ddMMyyyy','data' =>  date_create()))
            ->add('videoEndDate', 'date', array('label' => 'Limite video','format' => 'ddMMyyyy','data' =>  date_create()))
            ->add('file', null, array('label' => 'Imagem'))
            ->add('company', 'entity_hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Clube\SiteBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'clube_sitebundle_project';
    }
}
