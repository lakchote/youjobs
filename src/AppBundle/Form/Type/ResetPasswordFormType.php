<?php

namespace AppBundle\Form\Type;

use AppBundle\Validator\Constraints\UsernameExists;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => array(new UsernameExists(), new NotBlank()),
                'label' => 'Votre email'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe ne sont pas identiques.',
                'first_options' => ['label' => 'Votre nouveau mot de passe'],
                'second_options' => ['label' => 'Répétez le mot de passe'],
                'constraints' => array(new NotBlank(), new Length([
                    'min' => '6',
                    'minMessage' => 'Le mot de passe doit faire 6 caractères au moins.',
                    'max' => '12',
                    'maxMessage'  => 'Le mot de passe ne peut excéder 12 caractères.'
                ])),
            ])
            ->add('resetPassword', TextType::class,[
                'constraints' => array(new NotBlank()),
                'label' => 'La chaîne de caractères que vous avez reçu par mail'
            ]);
    }
}
