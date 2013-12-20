<?php

namespace Clube\SiteBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder            
			->add('birthDate', 'date', array(
				'label' => 'Data de nascimento',
				'format' => 'ddMMyyyy',
				'years' => range(date('Y') -95, date('Y')),
				'data' =>  date_create()
			))
            ->add('isAgree', null, array(
				'label' => 'Eu li e compreendi os termos e condições do Clube dos Criadores.'
			))
            ->add('gender', null, array(
				'label' => 'Gênero'
			));
    }

    public function getName()
    {
        return 'site_user_registration';
    }
	
	public function getBirthDate()
    {
        return 'site_user_registration';
    }
	
	public function getIsAgree()
    {
        return 'site_user_registration';
    }
	
	public function getGender()
    {
        return 'site_user_registration';
    }
}