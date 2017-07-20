<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\TypeAnnonce;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Description de l\'annonce',
            ])
            ->add('categorie', EntityType::class,  [
                'class' => Categorie::class,
                'choice_label' => 'titre',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Catégorie de l\'annonce'
            ])
            ->add('type', EntityType::class, [
                'class' => TypeAnnonce::class,
                'choice_label' => 'type',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Type du contrat'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class
        ]);
    }
}
