<?php

namespace Clube\SiteBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Nome'))
            ->add('logo', null, array('label' => 'Logo (250x250)'))
            ->add('background', null, array('label' => 'Capa (705x250)'))
            ->add('link', null, array('label' => 'Link amigável'))
            ->add('site', null, array('label' => 'Site'))
            ->add('detail', null, array('label' => 'Detalhes'))
            ->add('users', 'entity', array(
                'label' => 'Usuários',
                'label_attr' => array('class' => 'cc-form-multiple-label'),
                'class' => 'SiteBundle:User',
                'property' => 'username',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->orderBy('u.username', 'ASC');
                    }))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Clube\SiteBundle\Entity\Company'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'clube_sitebundle_company';
    }
}
