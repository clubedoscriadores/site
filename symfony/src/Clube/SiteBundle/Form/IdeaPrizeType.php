<?php

namespace Clube\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IdeaPrizeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prizeAmount', null, array('label' => 'Valor'))
            ->add('prizePlace', 'hidden')
            ->add('createDate')
            ->add('project', 'entity_hidden')
            ->add('idea', 'entity_hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Clube\SiteBundle\Entity\IdeaPrize'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'clube_sitebundle_ideaprize';
    }
}
