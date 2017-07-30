<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\TypeAnnonce;
use AppBundle\EventSubscriber\Form\SanitizeUserInput;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitulePoste', TextType::class, [
                'label' => 'Intitulé du poste'
            ])
            ->add('localisation', TextType::class, [
                'label'  => 'Localisation du poste'
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Description de l\'annonce',
                'attr' => ['style' => 'height:150px;'],
                'trim' => false
            ])
            ->add('categorie', EntityType::class,  [
                'class' => Categorie::class,
                'choice_label' => 'titre',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Secteur d\'activité'
            ])
            ->add('type', EntityType::class, [
                'class' => TypeAnnonce::class,
                'choice_label' => 'type',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Type du contrat'
            ]);
        $builder->addEventSubscriber(new SanitizeUserInput());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class
        ]);
    }
}
