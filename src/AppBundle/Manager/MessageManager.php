<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MessageManager
{

    private $tokenStorage;
    private $em;
    private $authorizationChecker;

    public function  __construct(TokenStorage $tokenStorage, EntityManager $em, AuthorizationChecker $authorizationChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function sendMessage(User $user, Message $data)
    {
        $data->setDateEnvoi(new \DateTime());
        $data->setAuteurMessage($this->tokenStorage->getToken()->getUser());
        $data->setUser($user);
        $this->em->persist($data);
        $this->em->flush();
    }

    public function answerMessage(Message $message, User $user, Message $data)
    {
        if($message->getAuteurMessage() !== $user) return;
        $message->setStatus(Message::MESSAGE_ANSWERED);
        $data->setDateEnvoi(new \DateTime());
        $data->setAuteurMessage($this->tokenStorage->getToken()->getUser());
        $data->setUser($user);
        $this->em->persist($data);
        $this->em->flush();
    }

    public function deleteMessage(Message $message)
    {
        if($this->tokenStorage->getToken()->getUser() !== $message->getUser()) {
            if(!$this->authorizationChecker->isGranted('ROLE_ADMIN')) return;
        }
        $this->em->remove($message);
        $this->em->flush();
    }

    public function viewMessage(Message $message)
    {
        $message->setStatus(Message::MESSAGE_READ);
        $this->em->persist($message);
        $this->em->flush();
    }
}
