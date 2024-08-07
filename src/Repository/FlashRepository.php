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

    public function countUserConnexions(int $userId): int
    {
        $result = $this->createQueryBuilder('flash')
            ->select('count(flash.flasher) as count')
            ->where('flash.flasher = :userId')
            ->andWhere('flash.isSuccess = 1')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['count'];
    }

    public function getUserScore(int $userId): int
    {
        $result = $this->createQueryBuilder('flash')
            ->select('sum(flash.score) as points')
            ->where('flash.flasher = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['points'];
    }

    public function countTeamConnexions(int $teamId): int
    {
        $result = $this->createQueryBuilder('flash')
            ->select('count(distinct flash.flasher) as connexions')
            ->innerJoin('flash.flasher', 'user')
            ->where('user.team = :teamId')
            ->andWhere('flash.isSuccess = 1')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['connexions'];
    }

    public function getTeamScore(int $teamId): int
    {
        $result = $this->createQueryBuilder('flash')
            ->select('sum(flash.score) as points')
            ->innerJoin('flash.flasher', 'user')
            ->where('user.team = :teamId')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['points'];
    }

    public function getScoresByTeam(): array
    {
        $result = $this->createQueryBuilder('flash')
            ->select([
                'team.name',
                'count(distinct flash.flasher) as connexions',
                'count(flash.flasher) as points',
            ])
            ->innerJoin('flash.flasher', 'user')
            ->innerJoin('user.team', 'team')
            ->where('flash.isSuccess = 1')
            ->groupBy('team.name')
            ->orderBy('points', 'desc')
            ->getQuery()
            ->getArrayResult();

        foreach ($result as $index => $team) {
            $result[$team['name']] = [
                'connexions' => $team['connexions'],
                'points' => $team['points'],
            ];
            unset($result[$index]);
        }

        return $result;
    }
}
