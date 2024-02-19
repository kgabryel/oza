<?php

namespace App\Repository;

use App\Entity\Unit;
use App\Entity\User;
use App\Model\Filter\Unit as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Unit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unit[]    findAll()
 * @method Unit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitRepository extends ServiceEntityRepository implements FilterForUser, FindForUser
{
    use FilterForUserTrait;
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unit::class);
    }

    public function filter(User $user, Model $unit): array
    {
        $builder = $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($unit->getName() !== '') {
            $builder->andWhere('lower(e.name) like lower(:name)')
                ->setParameter('name', sprintf('%%%s%%', $unit->getName()));
        }
        if ($unit->getShortcut() !== '') {
            $builder->andWhere('lower(e.shortcut) like lower(:shortcut)')
                ->setParameter('shortcut', sprintf('%%%s%%', $unit->getShortcut()));
        }
        if (!$unit->getUnits()->isEmpty()) {
            $builder->andWhere('e.main in (:units)')->setParameter('units', $unit->getUnits());
        }
        if ($unit->findMain() xor $unit->findSub()) {
            if ($unit->findMain()) {
                $builder->andWhere('e.main is null');
            } else {
                $builder->andWhere('e.main is not null');
            }
        }

        return $builder->getQuery()->getResult();
    }
}
