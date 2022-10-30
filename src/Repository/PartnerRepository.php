<?php

namespace App\Repository;

use App\Entity\Partner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partner>
 *
 * @method Partner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partner[]    findAll()
 * @method Partner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partner::class);
    }

    public function save(Partner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Partner $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrderedByName()
    {
        return $this->findBy([],['name'=>'asc']);
    }

//    Use query builder that is unsafe against SQL injection
//    To modify so it is a prepared statement query
    public function findByWord($keyword):array
    {
        $result=  $this
            ->createQueryBuilder('a')
            ->where('lower(a.name) LIKE :key')
            ->setParameter('key' , $keyword.'%')
            ->getQuery()
            ->getResult();

        return $result;
    }

//    /**
//     * @return Partner[] Returns an array of Partner objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Partner
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


// ------------- RESIDUS DE TESTS --------------------
//
//    public function findPartnerWithAssociatedStructureAndService(): ?array
//    {
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//        SELECT *
//        FROM partner p
//        LEFT OUTER JOIN
//            structure ON p.id = structure.partner_id
//        LEFT OUTER JOIN
//            partner_service ON p.id = partner_service.partner_id
//        LEFT OUTER JOIN
//            service ON partner_service.service_id = service.id
//        ORDER BY
//            p.name ASC,
//            `structure`.`address` ASC,
//            `service`.`title` ASC';
//
//        $stmt = $conn->prepare($sql);
//        $resultSet = $stmt->executeQuery([])->fetchAllAssociative();
//
//        return $resultSet;
//    }
//
//
//    public function findPartnerWithAssociatedStructure(): ?array
//    {
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//        SELECT *
//        FROM partner p
//        LEFT OUTER JOIN
//            structure ON p.id = structure.partner_id
//        LEFT OUTER JOIN
//            partner_service ON p.id = partner_service.partner_id
//        LEFT OUTER JOIN
//            service ON partner_service.service_id = service.id
//        ORDER BY
//            p.name ASC,
//            `structure`.`address` ASC,
//            `service`.`title` ASC';
//
//        $stmt = $conn->prepare($sql);
//        $resultSet = $stmt->executeQuery([])->fetchAllAssociative();
//
//        return $resultSet;
//    }


//    public function findPartnerWithAssociatedStructure(): ?array
//    {
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//        SELECT *
//        FROM partner p
//        LEFT OUTER JOIN
//            structure ON p.id = structure.partner_id
//
//        ORDER BY
//            p.name ASC,
//            `structure`.`address` ASC';
//
//
//        $stmt = $conn->prepare($sql);
//        $resultSet = $stmt->executeQuery([])->fetchAllAssociative();
//        return $resultSet;
//    }
//
//
//
//
//    public function findPartnerWithAssociatedService(): ?array
//    {
//        $conn = $this->getEntityManager()->getConnection();
//
//        $sql = '
//        SELECT *
//        FROM partner p
//
//        LEFT OUTER JOIN
//            partner_service ON p.id = partner_service.partner_id
//        LEFT OUTER JOIN
//            service ON partner_service.service_id = service.id
//        ORDER BY
//            p.name ASC,
//            `service`.`title` ASC';
//
//
//        $stmt = $conn->prepare($sql);
//        $resultSet = $stmt->executeQuery([])->fetchAllAssociative();
//
//        return $resultSet;
//
//    }
// ------------------------------------FIN RESIDUS -------------
}