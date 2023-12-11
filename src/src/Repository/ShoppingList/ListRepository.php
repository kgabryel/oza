<?php

namespace App\Repository\ShoppingList;

use App\Entity\ShoppingList\Position;
use App\Entity\ShoppingList\ShoppingList;
use App\Repository\FindForUser;
use App\Repository\FindTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShoppingList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShoppingList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShoppingList[]    findAll()
 * @method ShoppingList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListRepository extends ServiceEntityRepository implements FindForUser
{
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingList::class);
    }

    /**
     * @return ShoppingList[]
     */
    public function getListToDelete(): array
    {
        $qb = $this->_em->createQueryBuilder();
        $subQuery = $qb->select('l2.id')
            ->from(Position::class, 'p')
            ->innerJoin('p.list', 'l2')
            ->where('p.checked = false')
            ->getDQL();

        return $this->createQueryBuilder('l')
            ->innerJoin('l.user', 'u')
            ->innerJoin('u.settings', 's')
            ->where($qb->expr()->notIn('l.id', $subQuery))
            ->andWhere('s.deleteLists = true')
            ->andWhere("DATE_ADD(l.createdAt,s.deleteListDays,'day') <= CURRENT_DATE()")
            ->getQuery()
            ->getResult();
    }
}
