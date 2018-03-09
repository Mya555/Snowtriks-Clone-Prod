<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TricksRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tricks::class);
    }

  /*
   * Recherche de résultats , triés par la date décroissante, maximum = 10 résulatat affichés.
   */
    public function findAllTricks()
    {
        $query =  $this->createQueryBuilder('t')
            ->orderBy('t.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery();

        return
           $query->getResult();

    }


}
