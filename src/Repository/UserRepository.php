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
            ->andWhere('u.username = :code')
            ->andWhere('u.registeredAt IS NOT NULL')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getCountersByTeam(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT team.name AS team, registered.count AS registered, connected.count AS connected
                FROM 
                    (SELECT user.team_id, COUNT(user.username) AS count FROM user user WHERE user.registered_at IS NOT NULL GROUP BY user.team_id) AS registered
                LEFT JOIN 
                    (SELECT user.team_id, COUNT(flashes.user) AS count 
                        FROM (SELECT flasher_id AS user FROM flash WHERE is_success = 1 
                             UNION SELECT flashed_id AS user FROM flash WHERE is_success = 1) AS flashes
                    JOIN user user ON user.id = flashes.user
                    GROUP BY user.team_id) AS connected 
                    ON connected.team_id = registered.team_id
                JOIN team team ON registered.team_id = team.id';

        return $conn->executeQuery($sql)->fetchAllAssociative();
    }
}
