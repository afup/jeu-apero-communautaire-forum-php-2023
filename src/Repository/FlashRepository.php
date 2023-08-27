<?php

namespace App\Repository;

use App\Entity\Flash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Flash>
 *
 * @method Flash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Flash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Flash[]    findAll()
 * @method Flash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Flash::class);
    }
}
