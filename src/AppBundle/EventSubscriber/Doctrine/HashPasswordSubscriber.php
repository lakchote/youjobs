<?php


namespace AppBundle\EventSubscriber\Doctrine;


use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class HashPasswordSubscriber implements EventSubscriber
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if(!$entity instanceof User) return;
        $this->setDateInscription($entity);
        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if(!$entity instanceof User || $entity->getPlainPassword() === null) return;
        $this->encodePassword($entity);
        $em = $args->getEntityManager();
        $metaData = $em->getClassMetadata($entity);
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($metaData, $entity);
    }

    private function encodePassword(User $user)
    {
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encodedPassword);
    }

    private function setDateInscription(User $user)
    {
        $user->setDateInscription(new \DateTime());
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
}
