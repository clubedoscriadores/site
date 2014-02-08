<?php

namespace Clube\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IdeaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Título'))
            ->add('detail', null, array('label' => 'Detalhes'))
            ->add('createDate')
            ->add('isWinner')
            ->add('project', 'entity_hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Clube\SiteBundle\Entity\Idea'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'clube_sitebundle_idea';
    }
}
