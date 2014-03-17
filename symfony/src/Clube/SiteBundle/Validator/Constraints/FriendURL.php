<?php
namespace Clube\SiteBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class FriendURL extends Constraint
{
    public $message = 'Seu portfólio só pode conter letras e números minúsculos separados por "-"';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
