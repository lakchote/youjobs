<?php


namespace AppBundle\Service;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserAnnoncesActions
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function thankUserAnnonce($currentUser, User $user, Annonce $annonce) {
        /**
         * @var User $currentUser
         */
        $user->setNbRemerciements();
        $currentUser->setRemerciementsAnnonces($annonce->getId());
        $this->em->persist($user);
        $this->em->persist($currentUser);
        $this->em->flush();
    }

    public function reportAdvert($currentUser, Annonce $annonce) {
        /**
         * @var User $currentUser
         */
        $currentUser->setSignalementsAnnonces($annonce->getId());
        $annonce->setAnnonceSignalee(true);
        $this->em->persist($annonce);
        $this->em->persist($currentUser);
        $this->em->flush();
    }
}
