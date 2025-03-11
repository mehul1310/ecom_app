<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return array{items: iterable<int, Product>, total: int, currentPage: int, totalPages: int}
     */
    public function findPaginated
    (
        int $page,
        string $sortBy = 'title',
        ?string $category = null,
        int $limit = 10
    ): array {
        $queryBuilder = $this->createQueryBuilder('p')
            ->orderBy('p.' . $sortBy, 'ASC');

        if ($category) {
            $queryBuilder->join('p.category', 'c')
                ->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        return [
            'items' => iterator_to_array($paginator->getIterator()),
            'total' => count($paginator),
            'currentPage' => $page,
            'totalPages' => (int) ceil(count($paginator) / $limit)
        ];
    }
}
