<?php


namespace AppBundle\Service;


use AppBundle\Entity\Annonce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class PostAnnonce
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createAdvert($formData, User $user)
    {
        $advert = new Annonce();
        $advert->setIntitulePoste($formData['intitulePoste']->getData());
        $advert->setLocalisation($formData['localisation']->getData());
        $advert->setContenu($formData['contenu']->getData());
        $advert->setCategorie($formData['categorie']->getData());
        $advert->setType($formData['type']->getData());
        $advert->setDatePublication(new \DateTime());
        $advert->setUser($user);
        $this->em->persist($advert);
        $this->em->flush();
    }
}
