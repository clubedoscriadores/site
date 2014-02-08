<?php

namespace Clube\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoPrizeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prizeAmount', null, array('label' => 'Valor'))
            ->add('prizePlace')
            ->add('createDate')
            ->add('video', 'entity_hidden')
            ->add('project', 'entity_hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Clube\SiteBundle\Entity\VideoPrize'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'clube_sitebundle_videoprize';
    }
}
