<?php


namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\TypeAnnonce;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTypeAnnonce implements FixtureInterface
{
    private $types =
        [
            'CDD',
            'CDI',
            'Stage',
            'Intérim'
        ];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < count($this->types); $i++) {
            $type = new TypeAnnonce();
            $type->setType($this->types[$i]);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
