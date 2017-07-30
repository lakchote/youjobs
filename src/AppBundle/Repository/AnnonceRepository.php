<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class AnnonceRepository extends EntityRepository
{
    public function countAnnoncesForACategorie($titreCategorie)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->leftJoin('a.categorie', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAnnoncesForACategorie($titreCategorie)
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.datePublication', 'DESC')
            ->leftJoin('a.categorie', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getResult();
    }
}
