<?php

namespace App\Repository;

use App\Entity\Flash;
use App\Entity\User;
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
            ->select('sum(flash.score) as points')
            ->where('flash.flasher = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();

        return $result['points'] ?? 0;
    }

    public function getScoreByTeam(): array
    {
        $result = $this->createQueryBuilder('flash')
            ->select([
                'team.name',
                'sum(flash.score) as points',
            ])
            ->innerJoin('flash.flasher', 'user')
            ->innerJoin('user.team', 'team')
            ->groupBy('team.name')
            ->orderBy('points', 'desc')
            ->getQuery()
            ->getArrayResult();

        foreach ($result as $index => $team) {
            $result[$team['name']] = $team['points'];
            unset($result[$index]);
        }

        return $result;
    }

    public function getScoresByUser(User $user): array
    {
        $result = $this->createQueryBuilder('flash')
            ->select([
                'player.username',
                'player.name as playerName',
                'team.name as teamName',
                'sum(flash.score) as points',
            ])
            ->innerJoin('flash.flasher', 'player')
            ->innerJoin('player.team', 'team')
            ->groupBy('player.username, playerName, teamName')
            ->orderBy('points', 'desc')
            ->getQuery()
            ->getArrayResult();

        $ranking = [];

        for ($i = 0; $i < 3; $i++) {
            if (isset($result[$i])) {
                $ranking[$i + 1] = $result[$i];
            }
        }

        foreach ($result as $index => $player) {
            if ($player['username'] === $user->getUsername()) {
                $userRank = $index + 1;

                if ($userRank > 4) {
                    $ranking[$userRank - 1] = $result[$index - 1];
                }

                $ranking[$userRank] = $player;

                if ($userRank < count($result) && $userRank > 3) {
                    $ranking[$userRank + 1] = $result[$index + 1];
                }
            }
        }

        if (count($result) > 3) {
            $ranking[count($result)] = end($result);
        }

        return $ranking;
    }
}
