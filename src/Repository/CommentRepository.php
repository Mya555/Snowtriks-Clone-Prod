<?php
/**
 * Created by PhpStorm.
 * User: Bella
 * Date: 18/01/2018
 * Time: 21:34
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;


class CommentRepository extends EntityRepository
{
    public function getCommentsForTricks($tricksId, $approved = true)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->where('c.tricks = :tricks_id')
                   ->addOrderBy('c.created')
                   ->setParameter('tricks_id', $tricksId);

        if (false === is_null($approved))
            $qb->andWhere('c.approved = :approved')
               ->setParameter('approved', $approved);

        return $qb->getQuery()
                  ->getResult();
    }
}