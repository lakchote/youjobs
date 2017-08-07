<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Message;
use AppBundle\EventSubscriber\Form\SanitizeUserInput;
use function Symfony\Component\DependencyInjection\Tests\Fixtures\factoryFunction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => ['style' => 'height:200px;'],
                'trim' => false
            ]);
        $builder->addEventSubscriber(new SanitizeUserInput());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }

}
