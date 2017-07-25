<?php


namespace AppBundle\Service;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class UserAstucesActions
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function thankUserAstuce($currentUser, User $user, Astuce $astuce) {
        /**
         * @var User $currentUser
         */
        $user->setNbRemerciements();
        $currentUser->setRemerciementsAstuces($astuce->getId());
        $this->em->persist($user);
        $this->em->persist($currentUser);
        $this->em->flush();
    }

    public function reportAstuce($currentUser, Astuce $astuce) {
        /**
         * @var User $currentUser
         */
        $currentUser->setSignalementsAstuces($astuce->getId());
        $astuce->setAstuceSignalee(true);
        $this->em->persist($astuce);
        $this->em->persist($currentUser);
        $this->em->flush();
    }
}
