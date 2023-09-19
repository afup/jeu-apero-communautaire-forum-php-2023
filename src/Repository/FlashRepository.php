<?php

namespace App\Repository;

use App\Entity\Flash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findByFlasherIdAndFlashedCode(int $flasherId, string $flashedCode): ?Flash
    {
        return $this->createQueryBuilder('flash')
            ->innerJoin('flash.flashed', 'flashed')
            ->innerJoin('flash.flasher', 'flasher')
            ->where('flasher.id = :flasherId')
            ->andWhere('flashed.username = :flashedCode')
            ->setParameters([
                'flasherId' => $flasherId,
                'flashedCode' => $flashedCode,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
