<?php

namespace App\Repository;

use App\Entity\AlertType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlertType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlertType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlertType[]    findAll()
 * @method AlertType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlertType::class);
    }
}
