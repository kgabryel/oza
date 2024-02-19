<?php

namespace App\Repository\QuickList;

use App\Entity\QuickList\Position;
use App\Entity\QuickList\QuickList;
use App\Repository\FindForUser;
use App\Repository\FindTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuickList|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuickList|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuickList[]    findAll()
 * @method QuickList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListRepository extends ServiceEntityRepository implements FindForUser
{
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuickList::class);
    }

    /**
     * @return QuickList[]
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
            ->andWhere('s.deleteQuickLists = true')
            ->andWhere("DATE_ADD(l.createdAt,s.deleteQuickListDays,'day') <= CURRENT_DATE()")
            ->getQuery()
            ->getResult();
    }
}
