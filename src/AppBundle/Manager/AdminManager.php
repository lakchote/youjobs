<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Annonce;
use AppBundle\Entity\Astuce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class AdminManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function removeAnnonce(Annonce $annonce)
    {
        $this->em->remove($annonce);
        $this->em->flush();
    }

    public function removeAstuce(Astuce $astuce)
    {
        $this->em->remove($astuce);
        $this->em->flush();
    }

    public function removeUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function removeSignalementAnnonce(Annonce $annonce)
    {
        $annonce->setAnnonceSignalee(false);
        $this->em->remove($annonce);
        $this->em->flush();
    }

    public function removeSignalementAstuce(Astuce $astuce)
    {
        $astuce->setAstuceSignalee(false);
        $this->em->remove($astuce);
        $this->em->flush();
    }
}
