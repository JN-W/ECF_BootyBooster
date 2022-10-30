<?php

namespace App\Repository;

use App\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Structure>
 *
 * @method Structure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Structure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Structure[]    findAll()
 * @method Structure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Structure::class);
    }

    public function save(Structure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Structure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Structure[] Returns an array of Structure objects
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

    public function findOneByAddress($value): ?Structure
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.address = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    public function countStructureWithThisService($id)
//    {
//
//        return $this->createQueryBuilder('s')
//            ->leftJoin('structure_service','ss', 's.id = ss.structure_id')
//            ->andWhere('s.partner_id = :id')
//            ->setParameter('id', $id)
//            ->getQuery()
//            ->getResult()
//        ;
//
//         tentative de faire la même en sql directement
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//        SELECT * FROM structure s
//        LEFT OUTER JOIN structure_service ON s.id = structure_service.structure_id
//        WHERE s.partner_id=$id';
//
//        $stmt = $conn->prepare($sql);
//        $resultSet = $stmt->executeQuery([])->fetchAllAssociative();
//
//        return $resultSet;
//
//    }


}
