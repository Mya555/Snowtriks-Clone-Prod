<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 29/03/2018
 * Time: 10:02
 */

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Tests\Fixtures\Entity;



class CommentRepository extends EntityRepository
{
    public function findAllPagineEtTrie($page, $nbMaxPage, $nbMaxParPage)
    {
        if(!is_numeric($page)){
            throw new \InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }
        if($page < 1){
            throw new \InvalidArgumentException('La page demandée n\exite pas');
        }
        if (!is_numeric($nbMaxPage)){
            throw new \InvalidArgumentException(
                'La valeur de l\'argument $nbMaxParPage est incorrecte (valeur : ' . $nbMaxParPage . ').'
            );
        }
        $qb = $this->createQueryBuilder('a')
            ->where('CURRENT_DATE() >= a.dateCom')
            ->orderBy('a.dateCom', 'DESC');
        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);

        if (($paginator->count() <= $premierResultat) && $page != 1){
            throw new NotFoundHttpException('La page demandée n\'existe pas.');
        }
        return $paginator;
    }
}