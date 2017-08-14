<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class UsernameExists extends Constraint
{
    public $message = 'Aucun nom d\'utilisateur avec cette e-mail.';
}
