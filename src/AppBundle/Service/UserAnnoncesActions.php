<?php


namespace AppBundle\Service;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class UserAnnoncesActions
{
    private $em;
    private $tokenStorage;
    private $authorizationChecker;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage, AuthorizationChecker $authorizationChecker)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
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

    public function unThankUserAnnonce(User $currentUser, User $user, Annonce $id)
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

    public function deleteAdvert(Annonce $annonce)
    {
        if($this->tokenStorage->getToken()->getUser() !== $annonce->getUser()) {
            if(!$this->authorizationChecker->isGranted('ROLE_ADMIN')) return;
        }
        $this->em->remove($annonce);
        $this->em->flush();
    }
}
