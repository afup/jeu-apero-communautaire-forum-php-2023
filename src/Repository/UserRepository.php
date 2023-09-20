<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findRegisteredUser(string $code): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :code')
            ->andWhere('u.registeredAt IS NOT NULL')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function countRegisteredUsersByTeamId(int $teamId): int
    {
        $result = $this->createQueryBuilder('user')
            ->select('count(distinct user.id) as count')
            ->innerJoin('user.team', 'team')
            ->where('user.registeredAt IS NOT NULL')
            ->andWhere('team.id = :teamId')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result['count'];
    }
}
