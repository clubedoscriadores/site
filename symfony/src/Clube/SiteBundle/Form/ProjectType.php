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
            ->add('name')
            ->add('objective')
            ->add('detail')
            ->add('requirements')
            ->add('createDate')
            ->add('ideaEndDate')
            ->add('videoEndDate')
            ->add('totalPrize')
            ->add('projectStatus','entity',array(
                'class' => 'SiteBundle:ProjectStatus',
                'property' => 'name'
            ))
            ->add('company','entity',array(
                'class' => 'SiteBundle:Company',
                'property' => 'name'
            ))
            ->add('users')
            ->add('file')
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
