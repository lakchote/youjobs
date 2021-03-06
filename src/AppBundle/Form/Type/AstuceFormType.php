<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\CategorieAstuce;
use AppBundle\EventSubscriber\Form\SanitizeUserInput;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AstuceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intituleAstuce', TextType::class, [
                'label' => 'Intitulé de l\'astuce'
            ])
            ->add('categorieAstuce', EntityType::class, [
                'class' => CategorieAstuce::class,
                'choice_label' => 'titre',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Catégorie de l\'astuce'
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Description de l\'astuce',
                'attr' => ['style' => 'height:150px;'],
                'trim' => false
            ]);
        $builder->addEventSubscriber(new SanitizeUserInput());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Astuce::class
        ]);
    }
}
