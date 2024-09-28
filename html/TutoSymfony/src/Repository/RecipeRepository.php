<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return int
     */
    public function findTotalDuration()
    {
        return $this->createQueryBuilder('r')
            ->select('sum(r.duration) as total')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Recipe[]
     */
    public function findSelect(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.id', 'r.duration')
            ->orderBy('r.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $duration
     * @return Recipe[]
     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.duration <= :duration')
            ->setParameter('duration', $duration)
            ->setMaxResults(30)
            ->orderBy('r.duration', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
