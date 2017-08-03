<?php


namespace AppBundle\Service;


use AppBundle\Entity\Annonce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PostAnnonce
{

    private $em;
    private $tokenStorage;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function createAdvert(Annonce $annonce)
    {
        $annonce->setDatePublication(new \DateTime());
        $annonce->setUser($this->tokenStorage->getToken()->getUser());
        $this->em->persist($annonce);
        $this->em->flush();
    }
}
