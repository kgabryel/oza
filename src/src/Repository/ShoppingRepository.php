<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Shop;
use App\Entity\Shopping;
use App\Entity\User;
use App\Model\Filter\Shopping as Model;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shopping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopping[]    findAll()
 * @method Shopping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoppingRepository extends ServiceEntityRepository implements FindForUser
{
    use FindTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shopping::class);
    }

    public function filter(User $user, Model $shopping): array
    {
        $builder = $this->createQueryBuilder('e')
            ->innerJoin('e.unit', 'u')
            ->where('u.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($shopping->getDateFrom() !== null) {
            $builder->andWhere('e.date >= :dateFrom')->setParameter('dateFrom', $shopping->getDateFrom());
        }
        if ($shopping->getDateTo() !== null) {
            $builder->andWhere('e.date <= :dateTo')->setParameter('dateTo', $shopping->getDateTo());
        }
        if (!$shopping->getShops()->isEmpty()) {
            $builder->andWhere('e.shop in (:shops)')->setParameter('shops', $shopping->getShops());
        }
        if (!$shopping->getUnits()->isEmpty()) {
            $builder->andWhere('e.unit in (:units)')->setParameter('units', $shopping->getUnits());
        }
        if (!$shopping->getProductsGroups()->isEmpty()) {
            $productsQuery = $this->_em->createQueryBuilder()
                ->select('p.id')
                ->from(Product::class, 'p')
                ->innerJoin('p.groups', 'g')
                ->where('g.id in(:groups)')
                ->setParameter('groups', $shopping->getProductsGroups())
                ->getDQL();
            $builder->andWhere(
                $builder->expr()
                    ->orX(
                        $builder->expr()->andX('e.group in (:groups)'),
                        $builder->expr()->in('e.product', $productsQuery)
                    )
            )
                ->setParameter('groups', $shopping->getProductsGroups());
        }
        if (!$shopping->getProducts()->isEmpty()) {
            $builder->andWhere('e.product in (:products)')->setParameter('products', $shopping->getProducts());
        }

        return $builder->addOrderBy('e.date', 'DESC')->getQuery()->getResult();
    }

    public function getNewPricesForProducts(Product $product, ?Shop $shop, int $limit, ?int $maxResult = 1): array
    {
        $builder = $this->createQueryBuilder('sh')
            ->select('sh')
            ->andWhere('sh.product = :product')
            ->andWhere('sh.date >= :date')
            ->setParameter('product', $product)
            ->setParameter('date', (new DateTime())->modify(sprintf('-%s days', $limit)));
        if ($shop !== null) {
            $builder->andWhere('sh.shop = :shop')->setParameter('shop', $shop);
        }
        if ($maxResult !== null) {
            $builder->setMaxResults($maxResult);
        }

        return $builder->orderBy('sh.price', 'ASC')->getQuery()->getResult();
    }

    public function getNewPricesForProductsGroup(
        ProductsGroup $productsGroup,
        ?Shop $shop,
        int $limit,
        ?int $maxResult = 1
    ): array {
        $productsQuery = $this->_em->createQueryBuilder()
            ->select('p2.id')
            ->from(Product::class, 'p2')
            ->innerJoin('p2.groups', 'g2')
            ->where('g2.id = :productsGroup')
            ->getDQL();
        $builder = $this->createQueryBuilder('sh')
            ->select('sh');
        $builder->andWhere(
            $builder->expr()
                ->orX(
                    $builder->expr()->andX('sh.group = :productsGroup'),
                    $builder->expr()->in('sh.product', $productsQuery)
                )
        );
        if ($shop !== null) {
            $builder->andWhere('sh.shop = :shop')->setParameter('shop', $shop);
        }
        if ($maxResult !== null) {
            $builder->setMaxResults($maxResult);
        }

        return $builder->andWhere('sh.date >= :date')
            ->setParameter('productsGroup', $productsGroup)
            ->setParameter('date', (new DateTime())->modify(sprintf('-%s days', $limit)))
            ->orderBy('sh.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getOldPricesForProduct(Product $product, Shop $shop, int $limit): ?Shopping
    {
        return $this->createQueryBuilder('sh')
            ->select('sh')
            ->andWhere('sh.product = :product')
            ->andWhere('sh.shop = :shop')
            ->andWhere('sh.date < :date')
            ->setParameter('product', $product)
            ->setParameter('shop', $shop)
            ->setParameter('date', (new DateTime())->modify(sprintf('-%s days', $limit)))
            ->addOrderBy('sh.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getOldPricesForProductsGroup(ProductsGroup $productsGroup, Shop $shop, int $limit): ?Shopping
    {
        $productsQuery = $this->_em->createQueryBuilder()
            ->select('p2.id')
            ->from(Product::class, 'p2')
            ->innerJoin('p2.groups', 'g2')
            ->where('g2.id = :group')
            ->getDQL();
        $builder = $this->createQueryBuilder('sh')
            ->select('sh');
        $builder->andWhere(
            $builder->expr()
                ->orX(
                    $builder->expr()->andX('sh.group = :group'),
                    $builder->expr()->in('sh.product', $productsQuery)
                )
        );

        return $builder->andWhere('sh.shop = :shop')
            ->andWhere('sh.date < :date')
            ->setParameter('group', $productsGroup)
            ->setParameter('shop', $shop)
            ->setParameter('date', (new DateTime())->modify(sprintf('-%s days', $limit)))
            ->addOrderBy('sh.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
