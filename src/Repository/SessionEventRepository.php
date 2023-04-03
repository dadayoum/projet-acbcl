<?php

namespace App\Repository;

use App\Entity\SessionEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SessionEvent>
 *
 * @method SessionEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionEvent[]    findAll()
 * @method SessionEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionEvent::class);
    }

    public function add(SessionEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SessionEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return QueryBuilder Returns an array of Session objects
     */
    public function findAllSessionId(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC')
        ;
    }

//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
