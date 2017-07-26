<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class AstuceRepository extends EntityRepository
{
    public function getBetterRankedTips()
    {
        return $this
            ->createQueryBuilder('tips')
            ->orderBy('tips.nbRemerciements', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
