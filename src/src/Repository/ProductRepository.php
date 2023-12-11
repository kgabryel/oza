<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use App\Model\Filter\Product as Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements FilterForUser, FindForUser
{
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function filter(User $user, Model $product): array
    {
        $builder = $this->createQueryBuilder('e')
            ->where('e.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($product->getName() !== '') {
            $builder->andWhere('lower(e.name) like lower(:name)')
                ->setParameter('name', sprintf('%%%s%%', $product->getName()));
        }
        if ($product->getBarcode() !== '') {
            $builder->andWhere('lower(e.barcode) = :barcode')
                ->setParameter('barcode', $product->getBarcode());
        }
        if (!$product->getUnits()->isEmpty()) {
            $builder->andWhere('e.unit in (:units)')
                ->setParameter('units', $product->getUnits());
        }
        if (!$product->getProductsGroups()->isEmpty()) {
            $builder->innerJoin('e.groups', 'g', 'WITH', 'g.id in (:groups)')
                ->setParameter('groups', $product->getProductsGroups());
        }
        if (!$product->getProductsGroupUnits()->isEmpty()) {
            $builder->innerJoin('e.groups', 'gu')
                ->innerJoin('gu.unit', 'u', 'WITH', 'u.id in (:groupUnits)')
                ->setParameter('groupUnits', $product->getProductsGroupUnits());
        }
        if (!$product->getBrands()->isEmpty()) {
            $builder->andWhere('e.brand in (:brands)')
                ->setParameter('brands', $product->getBrands());
        }
        return $builder->getQuery()->getResult();
    }

    public function filterForUser(
        string $columnName,
        string $columnValue,
        string $userColumn,
        UserInterface $user,
        int $id
    ): array {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->innerJoin('e.unit', 'u')
            ->where(sprintf('lower(e.%s) = lower(:value)', $columnName))
            ->andWhere(sprintf('u.%s = :user', $userColumn))
            ->andWhere('e.id != :id')
            ->setParameter('id', $id)
            ->setParameter('value', $columnValue)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
