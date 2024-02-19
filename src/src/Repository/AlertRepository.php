<?php

namespace App\Repository;

use App\Entity\Alert;
use App\Entity\Supply;
use App\Entity\SupplyAlert;
use App\Entity\User;
use App\Model\Filter\Alert as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertRepository extends ServiceEntityRepository implements FindForUser
{
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alert::class);
    }

    public function filter(User $user, Model $alert): array
    {
        $builder = $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($alert->getDescription() !== '') {
            $builder->andWhere('lower(e.description) like lower(:description)')
                ->setParameter('description', sprintf('%%%s%%', $alert->getDescription()));
        }
        if (!$alert->getTypes()->isEmpty()) {
            $builder->andWhere('e.type in (:types)')->setParameter('types', $alert->getTypes());
        }
        if ($alert->findActive() xor $alert->findInactive()) {
            $builder->andWhere('e.isActive = :status')->setParameter('status', $alert->findActive());
        }

        return $builder->getQuery()->getResult();
    }

    public function findWithoutSupply(User $user, Supply $supply): array
    {
        $qb = $this->_em->createQueryBuilder();
        $subQuery = $qb->select('a.id')
            ->from(SupplyAlert::class, 'su')
            ->innerJoin('su.alert', 'a')
            ->where('su.supply = :supply_id')
            ->getDQL();

        return $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->andWhere($qb->expr()->notIn('e.id', $subQuery))
            ->setParameter('user_id', $user->getId())
            ->setParameter('supply_id', $supply->getId())
            ->getQuery()
            ->getResult();
    }

    public function getActiveAlerts(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.description', 't.type', 't.name')
            ->innerJoin('a.type', 't')
            ->where('a.user = :id')
            ->andWhere('a.isActive = true')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
