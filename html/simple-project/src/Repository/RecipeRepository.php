<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 *
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator
    ) {
        parent::__construct($registry, Recipe::class);
    }

    public function paginateRecipes(int $page, int $limit): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('r')
                ->leftJoin('r.category', 'c'),
            $page,
            $limit,
            [
                'distinct' => true
            ]
        );

        // return new Paginator($this->createQueryBuilder('r')
        //     ->setFirstResult(($page - 1) * $limit)
        //     ->setMaxResults($limit)
        //     ->getQuery()
        //     ->setHint(Paginator::HINT_ENABLE_DISTINCT, false), false);
    }

    /**
     *
     * @return Recipe[]
     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
            // ->select('r', 'c')
            ->leftJoin('r.category', 'c')
            ->where('r.duration <= :duration')
            ->setParameter('duration', $duration)
            ->orderBy('r.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
