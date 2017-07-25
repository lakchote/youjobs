<?php


namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class SetIntroMessagesAsRead
{
    private $tokenStorage;
    private $em;

    public function __construct(TokenStorage $tokenStorage, EntityManager $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function setAstucesMessageAsRead()
    {
        /**
         * @var User $user
         */
        $user = $this->tokenStorage->getToken()->getUser();
        $user->setMessageAstucesLu(true);
        $this->em->persist($user);
        $this->em->flush();
    }
}
