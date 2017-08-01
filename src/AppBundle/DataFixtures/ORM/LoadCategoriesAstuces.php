<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\CategorieAstuce;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoriesAstuces implements FixtureInterface
{

    private $categoriesAstuces =
        [
            'Accès à l\'emploi',
            'Retour à l\'emploi',
            'Cadre de vie professionnelle',
            'Entretiens',
            'Développement personnel',
            'Autre'
        ];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < count($this->categoriesAstuces); $i++) {
            $categorieAstuces = new CategorieAstuce();
            $categorieAstuces->setTitre($this->categoriesAstuces[$i]);
            $manager->persist($categorieAstuces);
        }
        $manager->flush();
    }
}
