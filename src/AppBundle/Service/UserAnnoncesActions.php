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

    public function thankUserAnnonce(User $currentUser, User $user, Annonce $annonce)
    {
        /**
         * @var User $currentUser
         */
        $user->setNbRemerciements();
        $currentUser->setRemerciementsAnnonces($annonce->getId());
        $this->em->persist($user);
        $this->em->persist($currentUser);
        $this->em->flush();
    }

    public function unThankUserAnnonce(User $currentUser, User $user, $id)
    {
        foreach($currentUser->getRemerciementsAnnonces() as $key => $value) {
            if($id->getId() == $value) {
                $currentUser->updateRemerciementsAnnonces($key);
            }
        }
        $user->removeRemerciement();
        $this->em->persist($currentUser);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function reportAdvert(User $currentUser, Annonce $annonce)
    {
        /**
         * @var User $currentUser
         */
        $currentUser->setSignalementsAnnonces($annonce->getId());
        $annonce->setAnnonceSignalee(true);
        $annonce->setNbSignalements();
        $this->em->persist($annonce);
        $this->em->persist($currentUser);
        $this->em->flush();
    }

    public function unReportAdvert(User $currentUser, Annonce $annonce)
    {
        foreach($currentUser->getSignalementsAnnonces() as $key => $value) {
            if($annonce->getId() == $value) {
                $currentUser->updateSignalementsAnnonces($key);
            }
        }
        if($annonce->getNbSignalements() == 0) $annonce->setAnnonceSignalee(false);
        $annonce->removeSignalement();
        $this->em->persist($annonce);
        $this->em->persist($currentUser);
        $this->em->flush();
    }
}
