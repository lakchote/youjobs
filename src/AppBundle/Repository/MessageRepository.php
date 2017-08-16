<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    public function countUnreadMessages(User $user)
    {
        return $this
            ->createQueryBuilder('m')
            ->select('COUNT(m)')
            ->where('m.user = :user')
            ->andWhere('m.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', Message::MESSAGE_UNREAD)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
