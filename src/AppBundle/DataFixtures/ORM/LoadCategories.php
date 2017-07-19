<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Categorie;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategories implements FixtureInterface
{
    private $categories =
        [
            'Agroalimentaire',
            'Banque / Assurance',
            'Bois / Papier / Carton / Imprimerie',
            'BTP / Matériaux construction',
            'Chimie / Parachimie',
            'Commerce / Négoce / Distribution',
            'Communication / Edition / Multimédia',
            'Electronique / Electricité',
            'Etudes et conseils',
            'Industrie pharmaceutique',
            'Informatique / Télécoms',
            'Machines et équipements / Automobile',
            'Métallurgie',
            'Plastique / Caoutchouc',
            'Services aux entreprises',
            'Textile / Habillement',
            'Transports / Logistique'
        ];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < count($this->categories); $i++) {
            $categorie = new Categorie();
            $categorie->setTitre($this->categories[$i]);
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
