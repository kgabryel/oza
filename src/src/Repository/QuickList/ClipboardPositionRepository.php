<?php

namespace App\Repository\QuickList;

use App\Entity\QuickList\ClipboardPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClipboardPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClipboardPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClipboardPosition[]    findAll()
 * @method ClipboardPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClipboardPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClipboardPosition::class);
    }
}
