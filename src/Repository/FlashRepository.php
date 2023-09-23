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

    public function getUserScore(int $userId): int
    {
        $result = $this->createQueryBuilder('flash')
            ->select('count(flash.flasher) as count')
            ->where('flash.flasher = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['count'];
    }

    public function getTeamScore(int $teamId): int
    {
        $result = $this->createQueryBuilder('flash')
            ->select('count(distinct flash.flasher) as count')
            ->innerJoin('flash.flasher', 'user')
            ->where('user.team = :teamId')
            ->andWhere('flash.isSuccess = 1')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['count'];
    }

    public function getScoresByTeam(): array
    {
        $result = $this->createQueryBuilder('flash')
            ->select('team.name, count(distinct flash.flasher) as count')
            ->innerJoin('flash.flasher', 'user')
            ->innerJoin('user.team', 'team')
            ->where('flash.isSuccess = 1')
            ->groupBy('team.name')
            ->orderBy('count', 'desc')
            ->getQuery()
            ->getArrayResult();

        foreach ($result as $index => $team) {
            $result[$team['name']] = $team['count'];
            unset($result[$index]);
        }

        return $result;
    }
}
