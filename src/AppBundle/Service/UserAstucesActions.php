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

    public function thankUserAstuce(User $currentUser, User $user, Astuce $astuce)
    {
        /**
         * @var User $currentUser
         */
        $user->setNbRemerciements();
        $currentUser->setRemerciementsAstuces($astuce->getId());
        $this->em->persist($user);
        $this->em->persist($currentUser);
        $this->em->flush();
    }

    public function unThankUserAstuce(User $currentUser, Astuce $astuce)
    {
        foreach($currentUser->getRemerciementsAstuces() as $key => $value) {
            if($astuce->getId() == $value) {
                $currentUser->updateRemerciementsAstuces($key);
            }
        }
        $astuce->removeRemerciement();
        $astuce->getUserAstuce()->removeRemerciement();
        $this->em->persist($currentUser);
        $this->em->persist($astuce);
        $this->em->flush();
    }

    public function reportAstuce($currentUser, Astuce $astuce)
    {
        /**
         * @var User $currentUser
         */
        $currentUser->setSignalementsAstuces($astuce->getId());
        $astuce->setAstuceSignalee(true);
        $astuce->setNbSignalements();
        $this->em->persist($astuce);
        $this->em->persist($currentUser);
        $this->em->flush();
    }
    public function unReportAstuce($currentUser, Astuce $astuce)
    {
        foreach($currentUser->getSignalementsAstuces() as $key => $value) {
            if($astuce->getId() == $value) {
                $currentUser->updateSignalementsAstuces($key);
            }
        }
        if($astuce->getNbSignalements() == 0) $astuce->setAstuceSignalee(false);
        $astuce->removeSignalement();
        $this->em->persist($astuce);
        $this->em->persist($currentUser);
        $this->em->flush();
    }

}
