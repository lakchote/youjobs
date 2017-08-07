<?php


namespace AppBundle\Service;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class UserAstucesActions
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

    public function reportAstuce(User $currentUser, Astuce $astuce)
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
    public function unReportAstuce(User $currentUser, Astuce $astuce)
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

    public function bookmarkAstuce(User $currentUser, Astuce $astuce)
    {
        $astuce->setUsersAstucesFavorites($currentUser);
        $this->em->persist($astuce);
        $this->em->flush();
    }

    public function unBookmarkAstuce(User $currentUser, Astuce $astuce)
    {
        $astuce->getUsersAstucesFavorites()->removeElement($currentUser);
        $this->em->persist($astuce);
        $this->em->flush();
    }

    public function deleteAstuce(Astuce $astuce)
    {
        if($this->tokenStorage->getToken()->getUser() !== $astuce->getUserAstuce()) {
            if(!$this->authorizationChecker->isGranted('ROLE_ADMIN')) return;
        }
        $this->em->remove($astuce);
        $this->em->flush();
    }
}
